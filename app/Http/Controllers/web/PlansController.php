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
}
