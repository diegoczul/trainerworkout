<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Subscription;

class SyncStripeTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-stripe-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Stripe::setApiKey(config('constants.STRIPETestsecret_key'));

        $this->info("Fetching invoices...");
        $invoices = \Stripe\Invoice::all(['limit' => 100]);

        foreach ($invoices->autoPagingIterator() as $invoice) {
            $subscriptionId = $invoice->subscription;
            $paymentIntent = $invoice->payment_intent;
            $invoiceId = $invoice->id;
            $amount = $invoice->amount_paid / 100;

            $this->line("Invoice: $invoiceId | Amount: $amount | Subscription: $subscriptionId");

            if (!$subscriptionId) {
                $this->warn("Skipping invoice $invoiceId — no subscription ID.");
                continue;
            }
            if (DB::table('trainer_earnings')->where('stripe_subscription_id', $subscriptionId)->where('payment_intent_id', $invoice->payment_intent)->exists()) {
                $this->warn("Earning already recorded for $subscriptionId / {$invoice->payment_intent}");
                continue;
            }

            $plan = DB::table('plans_users')->where('subscriptionStripeKey', $subscriptionId)->first();

            if (!$plan || !$plan->trainer_id) {
                $this->warn("Skipping invoice $invoiceId — no matching trainer for subscription $subscriptionId");
                continue;
            }

            DB::table('stripe_transactions')->insert([
                'stripe_id' => $invoice->customer ?? 'unknown',
                'type' => 'subscription',
                'trainer_id' => $plan->trainer_id,
                'user_id' => $plan->user_id,
                'plan_id' => $plan->plan_id,
                'amount' => $amount,
                'currency' => $invoice->currency,
                'status' => $invoice->status,
                'stripe_invoice_id' => $invoiceId,
                'subscription_id' => $subscriptionId,
                'raw_payload' => json_encode($invoice),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $plan = DB::table('plans_users')->where('subscriptionStripeKey', $subscriptionId)->first();
            if (!$plan) {
                $this->warn("No plans_users match for subscription: $subscriptionId");
                continue;
            }

            DB::table('trainer_earnings')->insert([
                'trainer_id' => $plan->trainer_id,
                'user_id' => $plan->user_id,
                'plan_id' => $plan->plan_id,
                'amount' => $amount,
                'status' => "frozen",
                'price' => $amount,
                'stripe_subscription_id' => $subscriptionId,
                'payment_intent_id' => $invoice->payment_intent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $this->info("✅ Recorded $amount for trainer {$plan->trainer_id} from invoice $invoiceId");
        }

        $this->info("✅ Sync complete.");
    }
}
