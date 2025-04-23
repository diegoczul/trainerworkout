<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Http\Libraries\Messages;
use App\Models\Clients;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\SessionsUsers;
use App\Models\TrainerSessions;
use App\Models\Workouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Memberships;
use App\Models\MembershipsUsers;
use App\Models\OrderItems;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Subscription;
use Illuminate\Support\Facades\DB;

class OrdersController extends BaseController
{
    public function index()
    {
        $user = Auth::user();

        return view("Store.cart")
            ->with("user", $user)
            ->with("cart", Session::get("cart"));
    }

    public function thankyou()
    {
        return view("thankyou");
    }

    public function checkoutPlan($planId)
    {
        $plan = \App\Models\Plans::findOrFail($planId);
        return view('Store.subscribe')->with('plan', $plan);
    }

    public function checkout()
    {
        $debug = config('app.debug');

        if (!Auth::check()) {
            Session::put("redirect", "StoreCheckout");
            Session::save();
            return redirect()->route("StoreCreateAccount")->with("message", __("messages.FirstCreateAnAccount"));
        }

        if (count(Session::get("cart")["items"]) == 0) {
            if (Auth::user()->userType == "Trainer") {
                return redirect()->route('Trainer', ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])->withErrors(__("messages.CartEmpty"));
            } else {
                return redirect()->route('Trainee', ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])->withErrors(__("messages.CartEmpty"));
            }
        }

        return view("Store.checkout")
            ->with("debug", $debug)
            ->with("cart", Session::get("cart"));
    }

    public function createAccount()
    {
        return view("Store.createAccountStore");
    }

    public function indexPaypage($package = "")
    {
        $array = explode("?", request()->fullUrl());

        if (count($array) > 1) {
            Session::put("utm", $array[1]);
        }

        return view("paypage")->with("package", $package);
    }

    public function processPaymentNoLogin($ignoreThis = "")
    {
        $debug = config('app.debug');

        $validation = Validator::make(request()->all(), [
            "email" => "email|required",
            "firstname" => "required",
            "lastname" => "required",
            "street" => "required",
            "city" => "required",
            "country" => "required",
            "province" => "required",
            "password" => "required"
        ]);

        if ($validation->fails()) {
            return back()->withInput()->withErrors($validation->messages());
        }

        $user = Users::firstOrNew(['email' => request()->get("email")]);

        if (!$user->exists) {
            $user->fill([
                'firstName' => request()->get("firstname"),
                'lastName' => request()->get("lastname"),
                'email' => request()->get("email"),
                'userType' => "Trainer",
                'city' => request()->get("city"),
                'province' => request()->get("province"),
                'country' => request()->get("country"),
                'street' => request()->get("street"),
                'postalCode' => request()->get("postalcode"),
                'password' => Hash::make(request()->get("password"))
            ]);
            $user->save();

            $user->sendActivationEmail();
            Auth::loginUsingId($user->id);
        }

        $membership = Memberships::find(request()->get("pay_num_of_accounts"));
        $subId = "";

        if ($membership->free == 0) {
            Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

            try {
                $token = request()->get('stripeToken');

                if ($user->stripeCheckoutToken == "") {
                    $customer = \Stripe\Customer::create([
                        "source" => $token,
                        "description" => $user->email,
                        "email" => $user->email
                    ]);

                    $user->stripeCheckoutToken = $customer->id;
                    $user->fourLastDigits = $customer->sources->data[0]->last4;
                    $user->typeOfCreditCard = $customer->sources->data[0]->brand;
                    $user->save();
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(__("messages.CreditDeclined"));
            }

            try {
                $customer = \Stripe\Customer::retrieve($user->stripeCheckoutToken);
                if ($customer->subscriptions->total_count == 0) {
                    $subscription = $customer->subscriptions->create(["plan" => $membership->idAPI]);
                    $subId = $subscription->id;
                } else {
                    $subscription = $customer->subscriptions->retrieve($customer->subscriptions->data[0]->id);
                    $subscription->plan = $membership->idAPI;
                    $subscription->save();
                    $subId = $subscription->id;
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(__("messages.ProblemCheckout"));
            }
        }

        MembershipsUsers::where("userId", $user->id)->where("id", "!=", $membership->id)->delete();

        $membershipUser = new MembershipsUsers;
        $membershipUser->userId = $user->id;
        $membershipUser->membershipId = $membership->id;
        $membershipUser->registrationDate = now();
        $membershipUser->expiry = $membership->durationType == "monthly" ? now()->addMonth() : now()->addYear();
        $membershipUser->subscriptionStripeKey = $subId;
        $membershipUser->save();

        return redirect()->route("thankyouPayment");
    }

    public function stripeAPISingleCheckout($total, $debug = false)
    {
        $user = Auth::user();
        $cart = Session::get("cart");

        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            $token = request()->get('stripeToken');

            if (empty(request()->get("oldCustomer"))) {
                if ($user->stripeCheckoutToken == "") {
                    $customer = \Stripe\Customer::create([
                        "source" => $token,
                        "description" => $user->email,
                        "email" => $user->email
                    ]);

                    $user->stripeCheckoutToken = $customer->id;
                    $user->save();
                }
            }

            $charge = \Stripe\Charge::create([
                "amount" => $cart["total"] * 100,
                "currency" => "usd",
                "customer" => empty(request()->get("oldCustomer")) ? $token : $user->stripeCheckoutToken,
                "description" => $user->email,
                "email" => $user->email
            ]);

            OrderItems::where("orderId", $cart["orderId"])
                ->whereIn("itemType", ["Workout", "Session"])
                ->update(["paid" => now()]);

            $user->fourLastDigits = $charge->source->last4;
            $user->typeOfCreditCard = $charge->source->brand;
            $user->save();

            return "";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function stripeAPIMembershipCheckout($membership, Request $request, $order = "", $debug = false)
    {
        $user = Auth::user();
        $cart = Session::get("cart");

        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            $token = $request->get('stripeToken');
            if (!$request->filled("oldCustomer")) {
                if ($user->stripeCheckoutToken == "") {
                    // Create a Stripe customer
                    $customer = Customer::create([
                        "description" => $user->email,
                        "email" => $user->email
                    ]);
                }
            } else {
                $customer = Customer::retrieve($user->stripeCheckoutToken);
            }

            // Attach payment method to customer
            $paymentMethod = PaymentMethod::retrieve($token);
            $paymentMethodId = $paymentMethod->id;
            $paymentMethod->attach(['customer' => $customer->id]);

            //  Set as the default payment method
            Customer::update($customer->id, [
                'invoice_settings' => ['default_payment_method' => $token]
            ]);

            // Retrieve updated customer to get default payment method details
            $customer = Customer::retrieve($customer->id);
            $paymentMethods = $customer->invoice_settings->default_payment_method ? PaymentMethod::retrieve($customer->invoice_settings->default_payment_method) : null;

            // Save details to user
            $user->stripeCheckoutToken = $customer->id;
            $user->fourLastDigits = $paymentMethods->card->last4 ?? "";
            $user->typeOfCreditCard = $paymentMethods->card->brand ?? "";
            $user->save();

            $subId = "";
            $customer = Customer::retrieve($user->stripeCheckoutToken);
            $subscriptions = Subscription::all(["customer" => $customer->id, "status" => "active"]);
            if ($subscriptions->data === []) {
                $subscription = Subscription::create([
                    "customer" => $customer->id,
                    "items" => [
                        ["price" => $membership]
                    ],
                    'default_payment_method' => $paymentMethodId,
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                ]);
            } else {
                $activeSubscription = $subscriptions->data[0];
                $currentItemId = $activeSubscription->items->data[0]->id;

                $subscription = Subscription::update($activeSubscription->id, [
                    'cancel_at_period_end' => false,
                    'proration_behavior' => 'create_prorations',
                    'items' => [
                        [
                            'id' => $currentItemId,
                            'price' => $membership,
                        ]
                    ],
                    'default_payment_method' => $paymentMethodId,
                    'expand' => ['latest_invoice.payment_intent'],
                ]);
            }

            return [
                'status' => true,
                'data' => [
                    'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
                    'subscription_id' => $subscription->id,
                    'payment_intent_id' => $subscription->latest_invoice->payment_intent->id,
                ]
            ];

            //            // Get the subscription ID
            //            $subId = $subscription->id;
            //
            //            MembershipsUsers::where("userId", $user->id)->delete();
            //
            //            OrderItems::where("orderId", $cart["orderId"])
            //                ->where("itemType", "Membership")
            //                ->update(["paid" => now(), "subscriptionStripeKey" => $subId]);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function stripeCancelUserMembership($membership, $order = "", $debug = false)
    {
        $user = Auth::user();
        $cart = Session::get("cart");

        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            $customer = \Stripe\Customer::retrieve($user->stripeCheckoutToken);

            if ($customer->subscriptions->total_count > 0) {
                $subscription = $customer->subscriptions->retrieve($customer->subscriptions->data[0]->id);
                $subscription->cancel();
            }

            MembershipsUsers::where("userId", $user->id)->delete();

            OrderItems::where("orderId", $cart["orderId"])
                ->where("itemType", "Membership")
                ->update(["paid" => now(), "subscriptionStripeKey" => ""]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function processPayment(Request $request)
    {

        $user = Auth::user();
        $order = null;

        if (Session::has("cart")) {
            $cart = Session::get("cart");

            if (!array_key_exists("orderId", $cart)) $cart["orderId"] = 0;

            if (isset($cart["orderId"]) && !empty($cart["orderId"])) {
                $order = Orders::find($cart["orderId"]);
            } else {
                $order = new Orders();
            }

            $order->userId = $user->id;
            $order->total = $cart["total"];
            $order->subtotal = $cart["subtotal"];
            $order->street = $request->get("street");
            $order->city = $request->get("city");
            $order->province = $request->get("province");
            $order->country = $request->get("country");
            $order->postalcode = $request->get("postalcode");
            $order->orderDate = now();
            $order->paidBy = "Paypal";
            $order->status = "Unpaid";
            $order->currency = "USD";
            $order->save();

            $cart["orderId"] = $order->id;
            Session::put("cart", $cart);

            $totalSinglePurchase = 0;
            $membershipPurchase = 0;
            $membership = "";

            foreach ($cart["items"] as $x => $item) {

                if (isset($item["orderItemId"]) && !empty($item["orderItemId"]) && OrderItems::find($item["orderItemId"])) {
                    $orderItem = OrderItems::find($item["orderItemId"]);
                } else {
                    $orderItem = new OrderItems();
                }

                if ($item["type"] == "Workout") {
                    $itemPurchased = Workouts::find($item["id"]);
                    $orderItem->itemType = "Workout";
                    $totalSinglePurchase += $itemPurchased->price;
                } elseif ($item["type"] == "Session") {
                    $itemPurchased = TrainerSessions::find($item["id"]);
                    $orderItem->itemType = "Session";
                    $totalSinglePurchase += $itemPurchased->price;
                } else {
                    $itemPurchased = Memberships::find($item["id"]);
                    $orderItem->itemType = "Membership";
                    $membershipPurchase += $itemPurchased->price;
                    $membership = $itemPurchased->id;
                }

                $orderItem->orderId = $order->id;
                $orderItem->itemId = $item["id"];
                $orderItem->paid = now();
                $orderItem->quantity = 1;
                $orderItem->price = $itemPurchased->price;
                $orderItem->save();

                $item["orderItemId"] = $orderItem->id;
                $cart["items"][$x] = $item;
            }

            Session::put("cart", $cart);

            $approvedSingle = false;
            $approvedMembership = false;

            $debug = Config::get('app.debug');

            if ($totalSinglePurchase > 0) {
                $result = $this->stripeAPISingleCheckout($totalSinglePurchase, $debug);
                if ($result == "") $approvedSingle = true;
                if ($result != "") return Redirect::back()->withErrors($result);
            } else {
                $approvedSingle = true;
            }

            if ($membershipPurchase > 0) {
                $mem = Memberships::find($membership);
                $result = $this->stripeAPIMembershipCheckout($mem->idAPI, $request, $order, $debug);
                if ($result == "") $approvedMembership = true;
                if ($result != "") return Redirect::back()->withErrors($result);
            } else {
                $approvedMembership = true;
            }

            if ($approvedSingle && $approvedMembership) {
                foreach ($cart["items"] as $item) {
                    $orderItem = OrderItems::find($item["orderItemId"]);

                    if ($item["type"] == "Workout") {
                        $itemPurchased = Workouts::find($item["id"]);
                        $workoutNew = $itemPurchased->replicate(['name', 'objectives', 'authorId']);
                        $workoutNew->shares = 0;
                        $workoutNew->views = 0;
                        $workoutNew->timesPerformed = 0;
                        $workoutNew->userId = Auth::user()->id;
                        $workoutNew->availability = "private";
                        $workoutNew->save();

                        Event::dispatch('addedAWorkoutMarket', [Auth::user(), $itemPurchased->id, $itemPurchased->price]);
                    }

                    if ($item["type"] == "Session") {
                        $itemPurchased = TrainerSessions::find($item["id"]);
                        if (!Clients::checkIfTrainerHasClient(Auth::user()->id)) {
                            $client = new Clients();
                            $client->userId = Auth::user()->id;
                            $client->trainerId = $itemPurchased->userId;
                            $client->approvedClient = 1;
                            $client->approvedTrainer = 1;
                            $client->save();

                            Notifications::insertDynamicNotification(
                                Messages::notifications("SessionClient"),
                                $itemPurchased->userId,
                                Auth::user()->id,
                                ["firstName" => Auth::user()->firstName, "lastName" => Auth::user()->lastName],
                                true
                            );
                        }
                        for ($x = 0; $x < $itemPurchased->numberOfSessions; $x++) {
                            $session = new SessionsUsers();
                            $session->userId = Auth::user()->id;
                            $session->trainerId = $itemPurchased->userId;
                            $session->orderItemId = $orderItem->id;
                            $session->type = "Session";
                            $session->save();
                        }
                    }

                    if ($item["type"] == "Membership") {
                        $itemPurchased = Memberships::find($item["id"]);
                        $membership = new MembershipsUsers();
                        $membership->userId = Auth::user()->id;
                        $membership->membershipId = $itemPurchased->id;
                        $membership->registrationDate = now();
                        $membership->expiry = $itemPurchased->durationType == "monthly" ? now()->addMonth() : now()->addYear();
                        $membership->subscriptionStripeKey = $orderItem->subscriptionStripeKey;
                        $membership->orderItemId = $orderItem->id;
                        $membership->save();
                    }
                }
            } else {
                return Redirect::back()->withInput()->withErrors(Lang::get("messages.ProblemCheckout"));
            }

            Session::forget('cart');

            return View::make("Store.thankYou")
                ->with("message", Lang::get("messages.CheckoutComplete"))
                ->with("user", $user)
                ->with("order", $order);
        } else {
            return Redirect::route(Auth::user()->userType, ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->withErrors(Lang::get("messages.NotFound"));
        }
    }

    public function removeFromCart($identifier)
    {
        $cart = Session::get("cart");
        $index = -1;

        foreach ($cart["items"] as $item) {
            if ($identifier == $item["identifier"]) {
                $index = $item["identifier"];
            }
        }

        if (array_key_exists($index, $cart["items"])) {
            array_splice($cart["items"], $index, 1);
            Session::put("cart", $cart);
            return Redirect::route("cart")->with("message", Lang::get("messages.ItemRemovedFromCart"));
        } else {
            return Redirect::route("cart")->withErrors(Lang::get("messages.NotFound"));
        }
    }

    public function nextIdentifier($cart)
    {
        $max = 0;

        if (count($cart["items"]) == 0) {
            return 1;
        }

        foreach ($cart["items"] as $item) {
            if ($max < $item["identifier"]) {
                $max = $item["identifier"];
            }
        }

        return $max + 1;
    }

    public function upgradePlan()
    {
        Session::forget('cart');
        $user = Auth::user();
        return View::make("Store.upgradePlan")
            ->with("user", $user)
            ->with("cart", Session::get("cart"));
    }

    public function emptyCart()
    {
        $cartObject = [
            "items" => [],
            "orderId" => 0,
            "quantity" => 0,
            "provincialTax" => 0,
            "federalTax" => 0,
            "subtotal" => 0,
            "total" => 0
        ];
        Session::put("cart", $cartObject);
        Session::save();
    }

    public function addToCart($workoutId, $type)
    {
        $user = Auth::user();
        $cartObject = [
            "items" => [],
            "orderId" => 0,
            "quantity" => 0,
            "provincialTax" => 0,
            "federalTax" => 0,
            "subtotal" => 0,
            "total" => 0
        ];

        if (!Session::has("cart")) {
            Session::put("cart", $cartObject);
        }

        $this->emptyCart();
        $cart = Session::get("cart");

        $cartObject["identifier"] = $this->nextIdentifier($cart);

        if ($type == "Workout") {
            $workout = Workouts::find($workoutId);
            $cartObject["id"] = $workoutId;
            $cartObject["orderItemId"] = null;
            $cartObject["type"] = "Workout";

            if ($workout) {
                $cart["items"][] = $cartObject;
                $cart["quantity"]++;
                $cart["subtotal"] += $workout->price;
                $cart["total"] += $workout->price;
                Session::put("cart", $cart);
                Session::save();
                return Redirect::route("StoreCheckout");
            } else {
                return Redirect::route("cartUpgradePlan")->withErrors(Lang::get("messages.NotFound"));
            }
        } elseif ($type == "Session") {
            $workout = TrainerSessions::find($workoutId);
            $cartObject["orderItemId"] = null;
            $cartObject["id"] = $workoutId;
            $cartObject["type"] = "Session";

            if ($workout) {
                $cart["items"][] = $cartObject;
                $cart["quantity"]++;
                $cart["subtotal"] += $workout->price;
                $cart["total"] += $workout->price;
                Session::put("cart", $cart);
                Session::save();
                return Redirect::route("StoreCheckout");
            } else {
                return Redirect::route("cartUpgradePlan")->withErrors(Lang::get("messages.NotFound"));
            }
        } else {
            $workout = Memberships::find($workoutId);
            $cartObject["id"] = $workoutId;
            $cartObject["orderItemId"] = null;
            $cartObject["type"] = "Membership";

            if ($workout) {
                $cart["items"][] = $cartObject;
                $cart["quantity"]++;
                $cart["subtotal"] += $workout->price;
                $cart["total"] += $workout->price;
                Session::put("cart", $cart);
                Session::save();
                return Redirect::route("StoreCheckout")->with('cart', $cart);
            } else {
                return Redirect::route("cartUpgradePlan")->withErrors(Lang::get("messages.NotFound"));
            }
        }
    }


    public function processSubscriptionPayment(Request $request)
    {
        $user = Auth::user();
        if (Session::has("cart")) {
            $cart = Session::get("cart");

            if (!array_key_exists("orderId", $cart)) $cart["orderId"] = 0;

            if (isset($cart["orderId"]) && !empty($cart["orderId"])) {
                $order = Orders::find($cart["orderId"]);
                if (!$order) {
                    $order = new Orders();
                }
            } else {
                $order = new Orders();
            }

            $order->userId = $user->id;
            $order->total = $cart["total"];
            $order->subtotal = $cart["subtotal"];
            $order->street = $request->get("street");
            $order->city = $request->get("city");
            $order->province = $request->get("province");
            $order->country = $request->get("country");
            $order->postalcode = $request->get("postalcode");
            $order->orderDate = now();
            $order->paidBy = "Paypal";
            $order->status = "Unpaid";
            $order->currency = "USD";
            $order->save();

            $cart["orderId"] = $order->id;
            Session::put("cart", $cart);


            $membershipPurchase = 0;
            $membership = "";
            foreach ($cart["items"] as $x => $item) {
                if (isset($item["orderItemId"]) && !empty($item["orderItemId"]) && OrderItems::find($item["orderItemId"])) {
                    $orderItem = OrderItems::find($item["orderItemId"]);
                } else {
                    $orderItem = new OrderItems();
                }

                $itemPurchased = Memberships::find($item["id"]);
                $orderItem->itemType = "Membership";
                $membershipPurchase += $itemPurchased->price;
                $membership = $itemPurchased->id;

                $orderItem->orderId = $order->id;
                $orderItem->itemId = $item["id"];
                $orderItem->paid = now();
                $orderItem->quantity = 1;
                $orderItem->price = $itemPurchased->price;
                $orderItem->save();

                $item["orderItemId"] = $orderItem->id;
                $cart["items"][$x] = $item;
            }
            Session::put("cart", $cart);

            $debug = Config::get('app.debug');
            $mem = Memberships::find($membership);
            $result = $this->stripeAPIMembershipCheckout($mem->idAPI, $request, $order, $debug);


            if ($result['status'] == true) {
                // Update Membership
                $subscriptionId = $result['data']['subscription_id'];
                $paymentIntentId = $result['data']['payment_intent_id'];
                MembershipsUsers::where("userId", $user->id)->delete();
                OrderItems::where("orderId", $cart["orderId"])
                    ->where("itemType", "Membership")
                    ->update(["paid" => now(), "subscriptionStripeKey" => $subscriptionId]);

                // Payment Status
                foreach ($cart["items"] as $item) {
                    $orderItem = OrderItems::find($item["orderItemId"]);
                    $itemPurchased = Memberships::find($item["id"]);
                    $membership = new MembershipsUsers();
                    $membership->userId = Auth::user()->id;
                    $membership->membershipId = $itemPurchased->id;
                    $membership->registrationDate = now();
                    $membership->expiry = $itemPurchased->durationType == "monthly" ? now()->addMonth() : now()->addYear();
                    $membership->subscriptionStripeKey = $orderItem->subscriptionStripeKey;
                    $membership->payment_intent_id = $paymentIntentId;
                    $membership->paid = now();
                    $membership->orderItemId = $orderItem->id;
                    $membership->save();
                }

                return $this::sendResponse('subscription', $result['data']);
            } else {
                return $this::sendError($result['message']);
            }
        } else {
            return $this::sendError(Lang::get("messages.NotFound"));
        }
    }

    public function processPlanSubscriptionPayment(Request $request)
    {
        $user = Auth::user();
        $debug = config('app.debug');
        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            $priceId = $request->get('plan_id'); // this should be the Stripe price ID
            $token = $request->get('stripeToken');

            // 1. Create or retrieve customer
            if (!$user->stripeCheckoutToken) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'description' => $user->email,
                ]);
                $user->stripeCheckoutToken = $customer->id;
                $user->save();
            }

            $customer = Customer::retrieve($user->stripeCheckoutToken);

            // 2. Attach payment method
            $paymentMethod = PaymentMethod::retrieve($token);
            $paymentMethod->attach(['customer' => $customer->id]);

            Customer::update($customer->id, [
                'invoice_settings' => ['default_payment_method' => $token],
            ]);

            $paymentMethod = PaymentMethod::retrieve($token); // Refresh
            $paymentMethodId = $paymentMethod->id; // 👈 This line was missing
            $user->fourLastDigits = $paymentMethod->card->last4 ?? '';
            $user->typeOfCreditCard = $paymentMethod->card->brand ?? '';
            $user->save();

            // 3. Create subscription
            $plan = \App\Models\Plan::findOrFail($request->plan_id);

            $subscription = Subscription::create([
                'customer' => $customer->id,
                'items' => [[
                    'price' => $plan->stripe_price_id // ✅ must be the string like "price_1RGxYJ..."
                ]],
                'default_payment_method' => $paymentMethodId,
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // 4. Insert into plans_users
            DB::table('plans_users')->insert([
                'trainer_id' => $plan->user_id,
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'stripe_price_id' => $plan->stripe_price_id,
                'subscriptionStripeKey' => $subscription->id,
                'payment_intent_id' => $subscription->latest_invoice->payment_intent->id,
                'price' => $plan->price,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'data' => [
                    'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
                    'subscription_id' => $subscription->id,
                    'payment_intent_id' => $subscription->latest_invoice->payment_intent->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function completeSubscriptionPayment(Request $request)
    {
        $user = Auth::user();
        $cart = Session::get("cart");
        $subscriptionId = $request->get('subscription_id');
        $paymentIntentId = $request->get('payment_intent_id');

        // Update Membership
        MembershipsUsers::where("userId", $user->id)->delete();
        OrderItems::where("orderId", $cart["orderId"])
            ->where("itemType", "Membership")
            ->update(["paid" => now(), "subscriptionStripeKey" => $subscriptionId]);

        // Payment Status
        foreach ($cart["items"] as $item) {
            $orderItem = OrderItems::find($item["orderItemId"]);
            $itemPurchased = Memberships::find($item["id"]);
            $membership = new MembershipsUsers();
            $membership->userId = Auth::user()->id;
            $membership->membershipId = $itemPurchased->id;
            $membership->registrationDate = now();
            $membership->expiry = $itemPurchased->durationType == "monthly" ? now()->addMonth() : now()->addYear();
            $membership->subscriptionStripeKey = $orderItem->subscriptionStripeKey;
            $membership->payment_intent_id = $paymentIntentId;
            $membership->paid = now();
            $membership->orderItemId = $orderItem->id;
            $membership->save();
        }
        return $this::sendSuccess("Subscription Complete");
    }

    public function successSubscription()
    {
        $user = Auth::user();
        if (Session::has("cart")) {
            $cart = Session::get("cart");
            $order = Orders::where('id', $cart["orderId"])->first();
            Session::forget('cart');
            return View::make("Store.thankYou")
                ->with("message", Lang::get("messages.CheckoutComplete"))
                ->with("user", $user)
                ->with("order", $order);
        } else {
            return Redirect::route(Auth::user()->userType, ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])->withErrors(Lang::get("messages.NotFound"));
        }
    }

    public function webhook(Request $request)
    {
        try {
            // HANDLES PAYMENT SUCCESS
            $data = $request->get('data');
            if ($request->get('type') == 'payment_intent.succeeded') {
                $payment_intent = $data['object']['id'];
                // UPDATING MEMBERSHIP USERS
                $membership_users = MembershipsUsers::where('payment_intent_id', $payment_intent)->first();
                $membership_users->paid = now();
                $membership_users->save();

                // UPDATING ORDER DETAILS
                $orderItem = OrderItems::select('orderId')->where('subscriptionStripeKey', $membership_users->subscriptionStripeKey)->first();
                Orders::where('id', $orderItem->orderId)->update(['status' => 'Paid', 'paidDate' => now()]);
            }

            if ($request->get('type') == 'payment_intent.payment_failed') {
                $payment_intent = $data['object']['id'];
                // UPDATING MEMBERSHIP USERS
                $membership_users = MembershipsUsers::where('payment_intent_id', $payment_intent)->first();
                $membership_users->paid = null;
                $membership_users->save();

                // UPDATING ORDER DETAILS
                $orderItem = OrderItems::select('orderId')->where('subscriptionStripeKey', $membership_users->subscriptionStripeKey)->first();
                Orders::where('id', $orderItem->orderId)->update(['status' => 'Failed']);
            }

            if ($request->get('type') == 'customer.subscription.updated') {
                $subscriptionId = $data['object']['id'] ?? "";
                // UPDATING MEMBERSHIP USERS
                $membership_users = MembershipsUsers::where(['subscriptionStripeKey' => $subscriptionId])->latest()->first();
                $membership_users->paid = now();
                $membership_users->save();

                // UPDATING ORDER DETAILS
                $orderItem = OrderItems::select('orderId')->where('subscriptionStripeKey', $membership_users->subscriptionStripeKey)->latest()->first();
                Orders::where('id', $orderItem->orderId)->update(['status' => 'Paid', 'paidDate' => now()]);
            }
        } catch (\Exception $exception) {
            Log::driver('webhook_exceptions_log')->error($exception->getMessage());
        }
    }
}
