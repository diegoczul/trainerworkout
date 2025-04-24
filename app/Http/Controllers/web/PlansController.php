<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Users;
use App\Models\Sharings;
use App\Jobs\SharedPlanEmailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{

    public function shareByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'planId' => 'required|integer|exists:plans,id',
            'email' => 'required|email',
            'message' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        $trainer = Auth::user();
        $plan = Plan::findOrFail($request->planId);

        // Ensure the trainer owns the plan
        if ($plan->user_id !== $trainer->id) {
            return response("You are not authorized to share this plan.", 403);
        }

        // Find or create the recipient
        $toUser = Users::firstOrCreate(
            ['email' => $request->email],
            ['userType' => 'Trainee']
        );

        // Link trainer and client
        $client = $trainer->addClient($toUser);
        $invite = $trainer->sendInvite($toUser);

        // Generate or find sharing link
        $link = sha1($trainer->id . $toUser->id . $plan->id . 'plan');

        $sharing = Sharings::updateOrCreate(
            ['access_link' => $link],
            [
                'fromUser' => $trainer->id,
                'toUser' => $toUser->id,
                'viewed' => 0,
                'accepted' => 0,
                'dateShared' => now(),
                'type' => 'plan',
                'aux' => $plan->id,
            ]
        );

        // Dispatch email
        SharedPlanEmailJob::dispatch(
            $sharing,
            $invite,
            $toUser,
            $trainer,
            $request->message,
            $request->copyMe === "true"
        );

        return response("Plan shared successfully.");
    }

    public function viewShared($link)
    {
        $sharing = Sharings::where('access_link', $link)->firstOrFail();

        $plan = Plan::findOrFail($sharing->aux);
        $trainer = Users::findOrFail($sharing->fromUser);

        return view('trainer.shared', [
            'plan' => $plan,
            'trainer' => $trainer,
            'link' => $link
        ]);
    }

    public function subscribe($planId)
    {
        $plan = Plan::findOrFail($planId);

        $cart = [
            'items' => [
                [
                    'id' => $plan->id,
                    'type' => 'Plan',
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'identifier' => 1,
                ]
            ],
            'subtotal' => $plan->price,
            'total' => $plan->price,
            'quantity' => 1,
        ];

        logger()->error('Plan Subscription', [
            'cart' => $cart,
            'plan' => $plan,
        ]);

        return view('Store.subscribe')->with('cart', $cart)->with('plan', $plan);
    }


    public function processSubscription(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);
        // Stripe logic based on $plan->stripe_price_id or $plan->stripeProduct
        // Copy your existing Stripe subscription logic, just inject this plan instead
    }

    public function viewClients($id)
    {
        $user = Auth::user();
        $plan = Plan::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $status = request('status', 'active'); // default to active

        $query = DB::table('plans_users')
            ->join('users', 'plans_users.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.firstName',
                'users.lastName',
                'users.email',
                'plans_users.status',
                'plans_users.started_at',
                'plans_users.renewals',
                'plans_users.price',
                'plans_users.created_at'
            )
            ->where('plans_users.plan_id', $id);

        if ($status !== 'all') {
            $query->where('plans_users.status', $status);
        }

        $clients = $query->get();




        return view('trainer.planClients', compact('plan', 'clients'));
    }

    public function cancelClient($plan_id, $user_id)
    {
        \Stripe\Stripe::setApiKey(config('app.debug') ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        $subscription = DB::table('plans_users')
            ->where('plan_id', $plan_id)
            ->where('user_id', $user_id)
            ->first();

        if ($subscription && $subscription->subscriptionStripeKey) {
            try {
                // Cancel at period end on Stripe
                \Stripe\Subscription::update($subscription->subscriptionStripeKey, [
                    'cancel_at_period_end' => true
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors("Stripe cancel error: " . $e->getMessage());
            }

            // Update DB
            DB::table('plans_users')
                ->where('plan_id', $plan_id)
                ->where('user_id', $user_id)
                ->update(['status' => 'cancelled', 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Subscription cancelled.');
    }

    public function cancelSelf($plan_id)
    {
        $user = Auth::user();

        $subscription = DB::table('plans_users')
            ->where('plan_id', $plan_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$subscription || !$subscription->subscriptionStripeKey) {
            return redirect()->back()->withErrors('Subscription not found.');
        }

        \Stripe\Stripe::setApiKey(config('app.debug') ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

        try {
            \Stripe\Subscription::update($subscription->subscriptionStripeKey, [
                'cancel_at_period_end' => true
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Stripe error: ' . $e->getMessage());
        }

        DB::table('plans_users')
            ->where('plan_id', $plan_id)
            ->where('user_id', $user->id)
            ->update(['status' => 'cancelled', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Your subscription has been set to cancel at the end of the billing period.');
    }
    public function payoutsIndex()
    {
        return view('ControlPanel.payouts');
    }
    public function payoutsData(Request $request)
    {
        $query = DB::table('trainer_payouts')
            ->join('users', 'users.id', '=', 'trainer_payouts.trainer_id')
            ->select(
                'trainer_payouts.id',
                'trainer_payouts.trainer_id',
                'trainer_payouts.amount',
                'trainer_payouts.status',
                'trainer_payouts.created_at',
                'trainer_payouts.paid_at',
                'users.firstName',
                'users.lastName',
                'users.email'
            );

        return datatables()->of($query)
            ->addColumn('trainer_name', fn($row) => $row->firstName . ' ' . $row->lastName)
            ->addColumn('mark_paid', function ($row) {
                if ($row->status === 'requested') {
                    return '<button onclick="markAsPaid(' . $row->id . ')" class="btn btn-sm btn-success">Mark as Paid</button>';
                }
                return $row->paid_at ? 'Paid on ' . \Carbon\Carbon::parse($row->paid_at)->format('M d, Y H:i') : '—';
            })
            ->rawColumns(['mark_paid'])
            ->make(true);
    }



    public function markPayoutAsPaid($id)
    {
        $payout = DB::table('trainer_payouts')->where('id', $id)->first();
        if (!$payout) return response()->json(['success' => false]);

        // Mark payout as paid
        DB::table('trainer_payouts')->where('id', $id)->update([
            'status' => 'paid',
            'updated_at' => now(),
        ]);

        // Update trainer_earnings
        DB::table('trainer_earnings')
            ->where('trainer_id', $payout->trainer_id)
            ->where(function ($query) {
                $query->where('status', 'available')
                    ->orWhereNull('status');
            })
            ->orderBy('created_at')
            ->get()
            ->reduce(function ($carry, $earning) use ($payout) {
                if ($carry >= $payout->amount) return $carry;

                $remaining = $payout->amount - $carry;
                $toPay = min($earning->amount - $earning->paid_out_amount, $remaining);

                DB::table('trainer_earnings')
                    ->where('id', $earning->id)
                    ->update([
                        'status' => 'paid',
                        'paid_out_amount' => DB::raw("paid_out_amount + $toPay")
                    ]);

                return $carry + $toPay;
            }, 0);

        return response()->json(['success' => true]);
    }
}
