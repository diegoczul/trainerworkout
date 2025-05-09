<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Subscription;

class UpdateCancelledPlans extends Command
{
    protected $signature = 'plans:sync-cancellations';
    protected $description = 'Sync cancelled Stripe subscriptions with local plans_users table';

    public function handle()
    {
        Stripe::setApiKey(config('constants.STRIPETestsecret_key'));

        $subscriptions = DB::table('plans_users')
            ->where('status', 'active')
            ->whereNotNull('stripe_subscription_id')
            ->get();

        foreach ($subscriptions as $sub) {
            try {
                $stripeSub = Subscription::retrieve($sub->stripe_subscription_id);

                if ($stripeSub->status === 'canceled') {
                    DB::table('plans_users')
                        ->where('id', $sub->id)
                        ->update([
                            'status' => 'cancelled',
                            'updated_at' => now()
                        ]);

                    $this->info("Cancelled subscription for user {$sub->user_id}");

                    if ($sub->pending_downgrade_to === 'monthly') {
                        // Create new monthly subscription
                        $monthlyPriceId = config('constants.STRIPE_MONTHLY_PRICE_ID');
                        $customerId = DB::table('users')->where('id', $sub->user_id)->value('stripeCheckoutToken');

                        $newSub = Subscription::create([
                            'customer' => $customerId,
                            'items' => [[
                                'price' => $monthlyPriceId
                            ]],
                            'payment_behavior' => 'default_incomplete',
                            'expand' => ['latest_invoice.payment_intent'],
                        ]);

                        DB::table('plans_users')->insert([
                            'trainer_id' => $sub->trainer_id,
                            'plan_id' => $sub->plan_id,
                            'user_id' => $sub->user_id,
                            'stripe_price_id' => $monthlyPriceId,
                            'subscriptionStripeKey' => $newSub->id,
                            'payment_intent_id' => $newSub->latest_invoice->payment_intent->id ?? null,
                            'price' => $sub->price, // or updated monthly price
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $this->info("✅ Created new MONTHLY subscription for user {$sub->user_id}");

                        DB::table('plans_users')
                            ->where('id', $sub->id)
                            ->update(['pending_downgrade_to' => null]); // Clear flag
                    }
                }

                if ($stripeSub->cancel_at_period_end) {
                    DB::table('plans_users')
                        ->where('id', $sub->id)
                        ->update([
                            'status' => 'scheduled_cancel',
                            'updated_at' => now()
                        ]);
                }
            } catch (\Exception $e) {
                $this->error("Error for sub {$sub->stripe_subscription_id}: " . $e->getMessage());
            }
        }


        return 0;
    }
}
