<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Http\Libraries\Messages;
use App\Models\AppleNotification;
use App\Models\Clients;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\SessionsUsers;
use App\Models\TrainerSessions;
use App\Models\UserApplePurchaseTransaction;
use App\Models\Workouts;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
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
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Subscription;
use Illuminate\Support\Facades\DB;
use App\Models\Plans;

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
                    'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret ?? null,
                    'subscription_id' => $subscription->id,
                    'payment_intent_id' => $subscription->latest_invoice->payment_intent->id ?? null,
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

    public function upgradePlan(Request $request)
    {
        Session::forget('cart');
        $user = Auth::user();
        if (!empty(Helper::getDeviceTypeCookie()) && Helper::getDeviceTypeCookie() == 'ios') {
            return View::make("webview.upgrade-plan")
                        ->with("user", $user)
                        ->with("cart", Session::get("cart"));
        }else{
            return View::make("Store.upgradePlan")
                ->with("user", $user)
                ->with("cart", Session::get("cart"));
        }

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

    public function requestDowngradeToMonthly(Request $request)
    {
        $user = Auth::user();
        $membership = $user->membership;

        if (!$membership || !$membership->subscriptionStripeKey) {
            return redirect()->back()->withErrors('No active yearly subscription to downgrade.');
        }

        \Stripe\Stripe::setApiKey(config('constants.STRIPETestsecret_key'));

        try {
            // 1. Set Stripe subscription to cancel at period end
            \Stripe\Subscription::update(
                $membership->subscriptionStripeKey,
                ['cancel_at_period_end' => true]
            );

            // 2. Store downgrade intent in DB
            DB::table('memberships_users')
                ->where('userId', $user->id)
                ->where('subscriptionStripeKey', $membership->subscriptionStripeKey)
                ->update([
                    'downgrade_to' => 'monthly',
                    'updated_at' => now()
                ]);

            return redirect()->route('MembershipManagement')->with('message', '✅ Your plan will downgrade to monthly after your current billing cycle ends.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error setting downgrade: ' . $e->getMessage());
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

            // ✅ NEW: If total is 0, skip Stripe
            if ($order->total <= 0) {
                MembershipsUsers::where("userId", $user->id)->update(["renew" => 0]);
                $user->cancelStripePlan();

                foreach ($cart["items"] as $item) {
                    $orderItem = OrderItems::find($item["orderItemId"]);
                    $itemPurchased = Memberships::find($item["id"]);
                    $membership = new MembershipsUsers();
                    $membership->userId = $user->id;
                    $membership->membershipId = $itemPurchased->id;
                    $membership->registrationDate = now();
                    $membership->expiry = $itemPurchased->durationType == "monthly" ? now()->addMonth() : now()->addYear();
                    $membership->subscriptionStripeKey = null;
                    $membership->payment_intent_id = null;
                    $membership->paid = now();
                    $membership->orderItemId = $orderItem->id;
                    $membership->renew = 0; // ✅ Disable auto-renewal
                    $membership->save();
                }

                return $this::sendResponse('subscription', ['message' => 'Free plan scheduled successfully']);
            }

            $debug = Config::get('app.debug');
            $mem = Memberships::find($membership);
            $result = $this->stripeAPIMembershipCheckout($mem->idAPI, $request, $order, $debug);


            if ($result['status'] == true) {
                // Update Membership
                $subscriptionId = $result['data']['subscription_id'] ?? null;
                $paymentIntentId = $result['data']['payment_intent_id'] ?? null;
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
        $debug = config('app.debug');
        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            $plan = \App\Models\Plan::findOrFail($request->plan_id);
            $token = $request->get('stripeToken');

            // Create or fetch the user by email
            $user = Auth::user();
            if (!$user) {
                $email = $request->get('email');
                $email = Helper::clean($email);
                $user = Users::where('email', $email)->first();

                if (!$user) {
                    $user = new Users;
                    $user->firstName = $request->get('first_name');
                    $user->lastName = $request->get('last_name');
                    $user->email = $email;
                    $user->street = $request->get('street');
                    $user->city = $request->get('city');
                    $user->province = $request->get('province');
                    $user->country = $request->get('country');
                    $user->postalCode = $request->get('postal');
                    $user->password = Hash::make(Str::random(12));
                    $user->userType = 'Trainee';
                    $user->save();

                    $user->sendActivationEmail();
                }

                Auth::loginUsingId($user->id); // Login the user if newly created
            }

            // Attach to trainer as client
            \App\Models\Clients::firstOrCreate([
                'trainerId' => $plan->user_id,
                'userId' => $user->id
            ]);

            // 1. Create or retrieve Stripe customer
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
            $user->fourLastDigits = $paymentMethod->card->last4 ?? '';
            $user->typeOfCreditCard = $paymentMethod->card->brand ?? '';
            $user->save();

            // 3. Create subscription
            $subscription = Subscription::create([
                'customer' => $customer->id,
                'items' => [[
                    'price' => $plan->stripe_price_id
                ]],
                'default_payment_method' => $paymentMethod->id,
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

    public function successSubscriptionPlan(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent_id'); // optional
        $user = Auth::user();

        // If paymentIntentId is passed (for added safety)
        if ($paymentIntentId) {
            $record = DB::table('plans_users')->where('payment_intent_id', $paymentIntentId)->first();

            if ($record) {
                if ($record->status !== 'active') {
                    DB::table('plans_users')
                        ->where('id', $record->id)
                        ->update(['status' => 'active']);
                }

                if ($user) {
                    return View::make("Store.thankYou")
                        ->with("message", Lang::get("messages.CheckoutComplete"))
                        ->with("user", $user)
                        ->with("order", (object)[
                            'subtotal' => $record->price,
                            'total' => $record->price,
                            'id' => $record->id
                        ]);
                } else {
                    return View::make("Store.thankYouNoLogin")
                        ->with("message", Lang::get("messages.CheckoutComplete"))
                        ->with("order", (object)[
                            'subtotal' => $record->price,
                            'total' => $record->price,
                            'id' => $record->id
                        ]);
                }
            }
        }

        // fallback
        return redirect('/')->withErrors("Something went wrong.");
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
            if (Auth::check()) {
                return Redirect::route(Auth::user()->userType, ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])->withErrors(Lang::get("messages.NotFound"));
            } else {
                return View::make("Store.thankYouNoLogin")
                    ->with("message", Lang::get("messages.CheckoutComplete"));
            }
        }
    }

    public function webhook(Request $request)
    {
        try {
            // HANDLES PAYMENT SUCCESS
            $data = $request->get('data');
            if ($request->get('type') === 'payment_intent.succeeded') {
                $payment_intent = $data['object']['id'];

                // ✅ Client subscribed to a trainer plan
                $planUser = DB::table('plans_users')->where('payment_intent_id', $payment_intent)->first();
                if ($planUser && $planUser->status !== 'active') {
                    DB::table('plans_users')->where('id', $planUser->id)->update(['status' => 'active']);

                    $exists = DB::table('trainer_earnings')
                        ->where('trainer_id', $planUser->trainer_id)
                        ->where('source', 'plan_subscription')
                        ->where('source_id', $planUser->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('trainer_earnings')->insert([
                            'trainer_id' => $planUser->trainer_id,
                            'user_id' => $planUser->user_id,
                            'amount' => $planUser->price,
                            'source' => 'plan_subscription',
                            'source_id' => $planUser->id,
                            'status' => 'available',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // ✅ Membership logic (trainer paying you)
                $membership_users = DB::table('memberships_users')->where('payment_intent_id', $payment_intent)->first();
                if ($membership_users) {
                    DB::table('memberships_users')->where('id', $membership_users->id)->update(['paid' => now()]);

                    $orderItem = DB::table('order_items')->select('orderId')->where('subscriptionStripeKey', $membership_users->subscriptionStripeKey)->first();
                    if ($orderItem) {
                        DB::table('orders')->where('id', $orderItem->orderId)->update(['status' => 'Paid', 'paidDate' => now()]);
                    }
                }
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

    public function processApplePurchase(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ref_user_id' => 'required|numeric|exists:users,id',
                'plan_id' => 'required|numeric|exists:memberships,id',
                'transaction_id' => 'required',
                'original_transaction_id' => 'required',
                'expires_date' => 'required',
                'product_id' => 'required',
            ]);
            if ($validator->fails()){
                return response()->json(['error'=>$validator->errors()], 400);
            }

            DB::beginTransaction();
            $transaction_id = $request->get('transaction_id');;
            $original_transaction_id = $request->get('original_transaction_id');
            $expires_date_ms = Carbon::createFromTimestampMs($request->get('expires_date'));
            $product_id = $request->get('product_id');

            // FINDING PLAN FROM PLAN ID
            $plan = Memberships::where('id', $request->get('plan_id'))->first();

            // GENERATING ORDER
            $order = new Orders();
            $order->fill([
                'userId' => $request->get('ref_user_id'),
                'orderDate' => now(),
                'subtotal' => $plan->price??0,
                'total' => $plan->price??0,
                'paidBy' => 'Apple',
                'status' => 'Paid',
                'currency' => 'USD',
            ])->save();

            // GENERATING ORDER ITEM
            $orderItem = new OrderItems();
            $orderItem->fill([
                'orderId' => $order->id,
                'itemId' => $plan->id,
                'itemType' => 'Membership',
                'quantity' => 1,
                'price' => $plan->price??0,
                'paid' => now(),
                'apple_transaction_id' => $transaction_id,
                'apple_original_transaction_id' => $original_transaction_id,
            ])->save();

            // REMOVING OLD MEMBERSHIP
            MembershipsUsers::where("userId", $request->get('ref_user_id'))->delete();

            // PAYMENT STATUS
            $membership = new MembershipsUsers();
            $membership->userId = $request->get('ref_user_id');
            $membership->membershipId = $plan->id;
            $membership->registrationDate = now();
            $membership->expiry = Carbon::parse($expires_date_ms);
            $membership->apple_transaction_id = $orderItem->apple_transaction_id;
            $membership->apple_original_transaction_id = $orderItem->apple_original_transaction_id;
            $membership->paid = now();
            $membership->orderItemId = $orderItem->id;
            $membership->save();

            // USER APPLE PURCHASE
            $userApplePurchase = new UserApplePurchaseTransaction();
            $userApplePurchase->fill([
                'ref_user_id' =>  $request->get('ref_user_id'),
                'original_transaction_id' => $orderItem->apple_original_transaction_id,
                'transaction_id' => $orderItem->apple_transaction_id,
                'transaction_type' => 'PURCHASE',
                'expiry_date' => Carbon::parse($expires_date_ms),
            ])->save();

            // COMPLETE DATABASE TRANSACTION
            DB::commit();

            // SENDING WEBHOOK
            $user = Users::find($request->get('ref_user_id'));
            $planResponse['apple_in_app_purchase_id'] = $plan->apple_in_app_purchase_id;
            $planResponse['price'] = $plan->price;
            $planResponse['durationType'] = $plan->durationType;
            $planResponse['expiry'] = Carbon::parse($expires_date_ms)->format('Y-m-d H:i:s');
            Event::dispatch('AppleSubscriptionPurchased',[$user,$planResponse]);

            // SENDING SUCCESS
            $response['redirect_url'] = route('login',['device_type' => 'ios']);
            return $this->sendResponse("receipt_data", $response);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->sendErrorResponse("Failed to verify Apple purchase",$exception->getMessage());
        }
    }

    public function restoreSubscription(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ref_user_id' => 'required|numeric|exists:users,id',
                'transaction_id' => 'required',
                'original_transaction_id' => 'required',
                'expires_date' => 'required',
                'product_id' => 'required',
            ]);
            if ($validator->fails()){
                return response()->json(['error'=>$validator->errors()], 400);
            }

            DB::beginTransaction();
            $transaction_id = $request->get('transaction_id');;
            $original_transaction_id = $request->get('original_transaction_id');
            $expires_date_ms = Carbon::createFromTimestampMs($request->get('expires_date'));
            $product_id = $request->get('product_id');

            // FINDING PLAN FROM PLAN ID
            $plan = Memberships::where('apple_in_app_purchase_id', $product_id)->orderBy('id','desc')->first();

            // GENERATING ORDER
            $order = new Orders();
            $order->fill([
                'userId' => $request->get('ref_user_id'),
                'orderDate' => now(),
                'subtotal' => $plan->price??0,
                'total' => $plan->price??0,
                'paidBy' => 'Apple',
                'status' => 'Paid',
                'currency' => 'USD',
            ])->save();

            // GENERATING ORDER ITEM
            $orderItem = new OrderItems();
            $orderItem->fill([
                'orderId' => $order->id,
                'itemId' => $plan->id,
                'itemType' => 'Membership',
                'quantity' => 1,
                'price' => $plan->price??0,
                'paid' => now(),
                'apple_transaction_id' => $transaction_id,
                'apple_original_transaction_id' => $original_transaction_id,
            ])->save();

            // REMOVING OLD MEMBERSHIP
            MembershipsUsers::where("userId", $request->get('ref_user_id'))->delete();

            // PAYMENT STATUS
            $membership = new MembershipsUsers();
            $membership->userId = $request->get('ref_user_id');
            $membership->membershipId = $plan->id;
            $membership->registrationDate = now();
            $membership->expiry = Carbon::parse($expires_date_ms);
            $membership->apple_transaction_id = $orderItem->apple_transaction_id;
            $membership->apple_original_transaction_id = $orderItem->apple_original_transaction_id;
            $membership->paid = now();
            $membership->orderItemId = $orderItem->id;
            $membership->save();

            // USER APPLE PURCHASE
            $userApplePurchase = new UserApplePurchaseTransaction();
            $userApplePurchase->fill([
                'ref_user_id' =>  $request->get('ref_user_id'),
                'original_transaction_id' => $orderItem->apple_original_transaction_id,
                'transaction_id' => $orderItem->apple_transaction_id,
                'transaction_type' => 'RESTORE',
                'expiry_date' => Carbon::parse($expires_date_ms),
            ])->save();

            // COMPLETE DATABASE TRANSACTION
            DB::commit();

            // SENDING WEBHOOK
            $user = Users::find($request->get('ref_user_id'));
            $planResponse['apple_in_app_purchase_id'] = $plan->apple_in_app_purchase_id;
            $planResponse['price'] = $plan->price;
            $planResponse['durationType'] = $plan->durationType;
            $planResponse['expiry'] = Carbon::parse($expires_date_ms)->format('Y-m-d H:i:s');
            Event::dispatch('AppleSubscriptionRestored',[$user,$planResponse]);

            // SENDING SUCCESS
            $response['redirect_url'] = route('login',['device_type' => 'ios']);
            return $this->sendResponse("receipt_data", $response);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->sendErrorResponse("Failed to verify Apple purchase",$exception->getMessage());
        }
    }


//    public function processApplePurchaseV2(Request $request)
//    {
//        try {
//            $validator = Validator::make($request->all(), [
//                'ref_user_id' => 'required|numeric|exists:users,id',
//                'plan_id' => 'required|numeric|exists:memberships,id',
//                'transaction_id' => 'required',
//                'original_transaction_id' => 'required',
//                'expires_date' => 'required',
//                'product_id' => 'required',
//            ]);
//            if ($validator->fails()){
//                return response()->json(['error'=>$validator->errors()], 400);
//            }
//
//            DB::beginTransaction();
//            $transaction_id = $request->get('transaction_id');;
//            $original_transaction_id = $request->get('original_transaction_id');
//            $expires_date_ms = Carbon::createFromTimestampMs($request->get('expires_date'));
//            $product_id = $request->get('product_id');
//
//            // FINDING PLAN FROM PLAN ID
//            $plan = Memberships::where('id', $request->get('plan_id'))->first();
//
//            // GENERATING ORDER
//            $order = new Orders();
//            $order->fill([
//                'userId' => $request->get('ref_user_id'),
//                'orderDate' => now(),
//                'subtotal' => $plan->price??0,
//                'total' => $plan->price??0,
//                'paidBy' => 'Apple',
//                'status' => 'Paid',
//                'currency' => 'USD',
//            ])->save();
//
//            // GENERATING ORDER ITEM
//            $orderItem = new OrderItems();
//            $orderItem->fill([
//                'orderId' => $order->id,
//                'itemId' => $plan->id,
//                'itemType' => 'Membership',
//                'quantity' => 1,
//                'price' => $plan->price??0,
//                'paid' => now(),
//                'apple_transaction_id' => $transaction_id,
//                'apple_original_transaction_id' => $original_transaction_id,
//            ])->save();
//
//            // REMOVING OLD MEMBERSHIP
//            MembershipsUsers::where("userId", $request->get('ref_user_id'))->delete();
//
//            // PAYMENT STATUS
//            $membership = new MembershipsUsers();
//            $membership->userId = $request->get('ref_user_id');
//            $membership->membershipId = $plan->id;
//            $membership->registrationDate = now();
//            $membership->expiry = Carbon::parse($expires_date_ms);
//            $membership->apple_transaction_id = $orderItem->apple_transaction_id;
//            $membership->apple_original_transaction_id = $orderItem->apple_original_transaction_id;
//            $membership->paid = now();
//            $membership->orderItemId = $orderItem->id;
//            $membership->save();
//
//            // USER APPLE PURCHASE
//            $userApplePurchase = new UserApplePurchaseTransaction();
//            $userApplePurchase->fill([
//                'ref_user_id' =>  $request->get('ref_user_id'),
//                'original_transaction_id' => $orderItem->apple_original_transaction_id,
//                'transaction_id' => $orderItem->apple_transaction_id,
//                'transaction_type' => 'PURCHASE',
//                'expiry_date' => Carbon::parse($expires_date_ms),
//            ])->save();
//
//            // COMPLETE DATABASE TRANSACTION
//            DB::commit();
//
//            // SENDING WEBHOOK
//            $user = Users::find($request->get('ref_user_id'));
//            $planResponse['apple_in_app_purchase_id'] = $plan->apple_in_app_purchase_id;
//            $planResponse['price'] = $plan->price;
//            $planResponse['durationType'] = $plan->durationType;
//            $planResponse['expiry'] = Carbon::parse($expires_date_ms)->format('Y-m-d H:i:s');
//            Event::dispatch('AppleSubscriptionPurchased',[$user,$planResponse]);
//
//            // SENDING SUCCESS
//            $response['redirect_url'] = route('login',['device_type' => 'ios']);
//            return $this->sendResponse("receipt_data", $response);
//        }catch (\Exception $exception){
//            DB::rollBack();
//            return $this->sendErrorResponse("Failed to verify Apple purchase",$exception->getMessage());
//        }
//    }
//
//    public function restoreSubscriptionV2(Request $request)
//    {
//        try {
//            $validator = Validator::make($request->all(), [
//                'ref_user_id' => 'required|numeric|exists:users,id',
//                'receipt_data' => 'required',
//                'payment_environment' => 'required'
//            ]);
//            if ($validator->fails()){
//                return response()->json(['error'=>$validator->errors()], 400);
//            }
//
//            DB::beginTransaction();
//            $verifyReceipt = $this->verifyApplePurchase($request->get('receipt_data'),$request->get('payment_environment'));
//            if (isset($verifyApplePurchase['status']) && !empty($verifyApplePurchase['status'])){
//                $latest_receipt_info = $verifyApplePurchase['data']['latest_receipt_info'][0]??[];
//
//                $transaction_id = $latest_receipt_info['transaction_id']??'';
//                $original_transaction_id = $latest_receipt_info['original_transaction_id']??'';
//                $expires_date_ms = $latest_receipt_info['expires_date_ms']??'';
//
//                $response['redirect_url'] = route('login',['device_type' => 'ios']);
//                $response['latest_receipt_info'] = $latest_receipt_info;
//                return $this->sendResponse("receipt_data", $response);
//            }else{
//                return $this->sendError($verifyApplePurchase['message']??"Failed to verify Apple purchase");
//            }
//
//            $transaction_id = $request->get('transaction_id');;
//            $original_transaction_id = $request->get('original_transaction_id');
//            $expires_date_ms = Carbon::createFromTimestampMs($request->get('expires_date'));
//            $product_id = $request->get('product_id');
//
//            // FINDING PLAN FROM PLAN ID
//            $plan = Memberships::where('apple_in_app_purchase_id', $product_id)->orderBy('id','desc')->first();
//
//            // GENERATING ORDER
//            $order = new Orders();
//            $order->fill([
//                'userId' => $request->get('ref_user_id'),
//                'orderDate' => now(),
//                'subtotal' => $plan->price??0,
//                'total' => $plan->price??0,
//                'paidBy' => 'Apple',
//                'status' => 'Paid',
//                'currency' => 'USD',
//            ])->save();
//
//            // GENERATING ORDER ITEM
//            $orderItem = new OrderItems();
//            $orderItem->fill([
//                'orderId' => $order->id,
//                'itemId' => $plan->id,
//                'itemType' => 'Membership',
//                'quantity' => 1,
//                'price' => $plan->price??0,
//                'paid' => now(),
//                'apple_transaction_id' => $transaction_id,
//                'apple_original_transaction_id' => $original_transaction_id,
//            ])->save();
//
//            // REMOVING OLD MEMBERSHIP
//            MembershipsUsers::where("userId", $request->get('ref_user_id'))->delete();
//
//            // PAYMENT STATUS
//            $membership = new MembershipsUsers();
//            $membership->userId = $request->get('ref_user_id');
//            $membership->membershipId = $plan->id;
//            $membership->registrationDate = now();
//            $membership->expiry = Carbon::parse($expires_date_ms);
//            $membership->apple_transaction_id = $orderItem->apple_transaction_id;
//            $membership->apple_original_transaction_id = $orderItem->apple_original_transaction_id;
//            $membership->paid = now();
//            $membership->orderItemId = $orderItem->id;
//            $membership->save();
//
//            // USER APPLE PURCHASE
//            $userApplePurchase = new UserApplePurchaseTransaction();
//            $userApplePurchase->fill([
//                'ref_user_id' =>  $request->get('ref_user_id'),
//                'original_transaction_id' => $orderItem->apple_original_transaction_id,
//                'transaction_id' => $orderItem->apple_transaction_id,
//                'transaction_type' => 'RESTORE',
//                'expiry_date' => Carbon::parse($expires_date_ms),
//            ])->save();
//
//            // COMPLETE DATABASE TRANSACTION
//            DB::commit();
//
//            // SENDING WEBHOOK
//            $user = Users::find($request->get('ref_user_id'));
//            $planResponse['apple_in_app_purchase_id'] = $plan->apple_in_app_purchase_id;
//            $planResponse['price'] = $plan->price;
//            $planResponse['durationType'] = $plan->durationType;
//            $planResponse['expiry'] = Carbon::parse($expires_date_ms)->format('Y-m-d H:i:s');
//            Event::dispatch('AppleSubscriptionRestored',[$user,$planResponse]);
//
//            // SENDING SUCCESS
//            $response['redirect_url'] = route('login',['device_type' => 'ios']);
//            return $this->sendResponse("receipt_data", $response);
//        }catch (\Exception $exception){
//            DB::rollBack();
//            return $this->sendErrorResponse("Failed to verify Apple purchase",$exception->getMessage());
//        }
//    }

    public function verifyApplePurchase($receipt_data,$payment_environment)
    {
        if ($payment_environment == 'production') {
            $url = "https://buy.itunes.apple.com/verifyReceipt";
        }else{
            $url = "https://sandbox.itunes.apple.com/verifyReceipt";
        }
        $apple_secret = config('constants.APPLE_PURCHASE_PASSWORD');
        $params['password'] = $apple_secret;
        $params['receipt-data'] = $receipt_data;
        $params['exclude-old-transactions'] = true;
        $response = Http::accept('application/json')
            ->post($url, $params);
        if ($response->successful()) {
            $response = $response->json();
            if ($response['status'] == 21000) {
                return $this::sendError("The App Store could not read the JSON object you provided.");
            }
            if ($response['status'] == 21002) {
                return $this::sendError("The data in the receipt-data property was malformed or missing.");
            }
            if ($response['status'] == 21004) {
                return $this::sendError("The receipt could not be authenticated.");
            }
            if ($response['status'] == 21007) {
                return $this::sendError("This transaction is not allowed to make a purchase.");
            }

            return $this->sendResponse("receipt_data", $response);
        }else{
            return $this::sendError("Something went wrong.");
        }
    }

    public function appleWebhook(Request $request)
    {
        try {
            $postData = json_decode($request->getContent(),true);
            if ($postData && isset($postData['signedPayload']) && !empty($postData['signedPayload'])) {
                $response = array();
                $isActive = false;
                $signedPayload = $postData['signedPayload'];
                $transaction = $this::decodeSignedPayload($signedPayload,true);
                $transaction['signedPayload'] = $signedPayload;

                $currentDate = time();

                if (isset($transaction['data']['signedTransactionInfo'])) {
                    $signedTransaction = $transaction['data']['signedTransactionInfo'];
                    $transactionDetails = $this::decodeSignedTransaction($signedTransaction,true);
                    $transaction['data']['signedTransaction'] = $transactionDetails;
                    $transaction['data']['signedTransaction']['signedDate_human'] = Carbon::createFromTimestampMs($transactionDetails['signedDate'])->timezone('UTC');
                    $transaction['data']['signedTransaction']['purchaseDate_human'] = Carbon::createFromTimestampMs($transactionDetails['purchaseDate'])->timezone('UTC');
                    $transaction['data']['signedTransaction']['expiresDate_human'] = Carbon::createFromTimestampMs($transactionDetails['expiresDate'])->timezone('UTC');
                    $transaction['data']['signedTransaction']['currentDate_human'] = Carbon::now()->timezone('UTC');

                    if (isset($transactionDetails['expiresDate'])) {
                        // Convert the milliseconds to seconds
                        $expiresDate = $transactionDetails['expiresDate'] / 1000;

                        // Create DateTime objects for clarity
                        $currentDateTime = Carbon::now()->timezone('UTC');
                        $expiresDateTime = Carbon::createFromTimestampMs($expiresDate)->timezone('UTC');

                        // Now you can compare
                        if ($expiresDateTime > $currentDateTime) {
                            $isActive = true;
                        }
                    }
                }

                if (isset($transaction['data']['signedRenewalInfo'])) {
                    $signedTransaction = $transaction['data']['signedRenewalInfo'];
                    $transactionDetails = $this::decodeSignedTransaction($signedTransaction);
                    $transaction['data']['signedRenewal'] = $transactionDetails;
                    $transaction['data']['signedRenewal']['signedDate_human'] =  Carbon::createFromTimestampMs($transactionDetails['signedDate'])->timezone('UTC');
                    $transaction['data']['signedRenewal']['renewalDate_human'] = Carbon::createFromTimestampMs($transactionDetails['renewalDate'])->timezone('UTC');
                    $transaction['data']['signedRenewal']['currentDate_human'] = Carbon::now()->timezone('UTC');
                }

                $response["is_active"] = $isActive;
                $response["original_transaction_id"] = isset($transaction['data']['signedTransaction']['originalTransactionId']) ? $transaction['data']['signedTransaction']['originalTransactionId'] : '';
                $response["transaction_id"] = isset($transaction['data']['signedTransaction']['transactionId']) ? $transaction['data']['signedTransaction']['transactionId'] : '';
                $response["transaction_status"] = isset($transaction['data']['signedTransaction']['transactionReason']) ? $transaction['data']['signedTransaction']['transactionReason'] : '';
                $response["transaction_env"] = isset($transaction['data']['signedTransaction']['environment']) ? $transaction['data']['signedTransaction']['environment'] : '';
                $response["transaction_type"] = isset($transaction['data']['signedTransaction']['type']) ? $transaction['data']['signedTransaction']['type'] : '';

                $response["notification_type"] = isset($transaction['notificationType']) ? $transaction['notificationType'] : '';

                $response["iap_id"] = isset($transaction['data']['signedTransaction']['productId']) ? $transaction['data']['signedTransaction']['productId'] : '';
                $response["price"] = isset($transaction['data']['signedTransaction']['price']) ? $transaction['data']['signedTransaction']['price'] : '';
                $response["price_currency"] = isset($transaction['data']['signedTransaction']['currency']) ? $transaction['data']['signedTransaction']['currency'] : '';

                $response["purchase_date"] = isset($transaction['data']['signedTransaction']['signedDate_human']) ? $transaction['data']['signedTransaction']['signedDate_human'] : '';
                $response["expiry_date"] = isset($transaction['data']['signedTransaction']['expiresDate_human']) ? $transaction['data']['signedTransaction']['expiresDate_human'] : '';
                $response["renewal_date"] = isset($transaction['data']['signedRenewal']['renewalDate_human']) ? $transaction['data']['signedRenewal']['renewalDate_human'] : '';

                $response["is_auto_renew"] = isset($transaction['data']['signedRenewal']['autoRenewStatus']) ? $transaction['data']['signedRenewal']['autoRenewStatus'] : '';

                $response["transaction_json"] = json_encode($transaction);

                $notification = new AppleNotification();
                $notification->fill($response);
                $notification->save();
                return $this->sendSuccess("Verified Apple purchase");
            }else{
                return $this->sendError("Invalid json response");
            }
        }catch (\Exception $exception) {
            \Log::error('Apple Webhook Error: ' . $exception->getMessage(), [
                'trace' => $exception->getTraceAsString()
            ]);
            return $this->sendError($exception->getMessage());
        }
    }

    // Function to decode signed payload (used in server notifications)
    public static function decodeSignedPayload($signedPayload)
    {
        list($header, $payload, $signature) = explode('.', $signedPayload);
        return json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    }

    public static function decodeSignedTransaction($signedTransaction)
    {
        list($header, $payload, $signature) = explode('.', $signedTransaction);
        return json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    }

    public function cancelDowngrade(Request $request)
    {
        Session::forget('cart');
        if (!Auth::user()->membership) {
            Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));
        } else {
            Auth::user()->membership->renew = 1;
            Auth::user()->membership->save();
            Auth::user()->resumeStripePlan(); // <- call here

        }

        if (!empty(Helper::getDeviceTypeCookie()) && Helper::getDeviceTypeCookie() == 'ios') {
            return View::make("webview.membership-management")->with("message", Lang::get("messages.downgrade_cancelled"));
        }else{
            return View::make("MembershipManagement")->with("message", Lang::get("messages.downgrade_cancelled"));
        }
    }

    public function CancelDowngradeYearly(Request $request)
    {
        Session::forget('cart');
        if (!Auth::user()->membership) {
            Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));
        } else {
            Auth::user()->membership->renew = 1;
            Auth::user()->membership->downgrade_to = null;
            Auth::user()->membership->save();
            Auth::user()->resumeStripePlan(); // <- call here

        }

        if (!empty(Helper::getDeviceTypeCookie()) && Helper::getDeviceTypeCookie() == 'ios') {
            return View::make("webview.membership-management")->with("message", Lang::get("messages.downgrade_cancelled"));
        }else{
            return View::make("MembershipManagement")->with("message", Lang::get("messages.downgrade_cancelled"));
        }
    }
}
