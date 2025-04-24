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

                if ($stripeSub->status === 'canceled' || $stripeSub->cancel_at_period_end) {
                    DB::table('plans_users')
                        ->where('id', $sub->id)
                        ->update([
                            'status' => 'cancelled',
                            'updated_at' => now()
                        ]);
                    $this->info("Cancelled subscription for user {$sub->user_id}");
                }
            } catch (\Exception $e) {
                $this->error("Error for sub {$sub->stripe_subscription_id}: " . $e->getMessage());
            }
        }

        return 0;
    }
}
