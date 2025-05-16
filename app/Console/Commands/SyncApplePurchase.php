<?php

namespace App\Console\Commands;

use App\Models\AppleNotification;
use App\Models\MembershipsUsers;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SyncApplePurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:apple-purchase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to sync apple purchase notifications with actual user transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AppleNotification::where('is_verified',0)->get()->each(function ($notification) {
            $original_transaction_id = $notification->original_transaction_id;
            $transaction_id = $notification->transaction_id;
            $expiry_date = $notification->expiry_date;
            $MembershipsUsers = MembershipsUsers::where([
                'apple_original_transaction_id' => $original_transaction_id,
                'deleted_at' => null
            ])->count();
            if ($MembershipsUsers > 0){
                // UPDATE MEMBERSHIP EXPIRY
                MembershipsUsers::where([
                    'apple_original_transaction_id' => $original_transaction_id,
                    'deleted_at' => null
                ])->update(['expiry' => Carbon::parse($expiry_date)]);

                // UPDATE NOTIFICATION VERIFICATION STATUS
                $notification->is_verified = 1;
                $notification->save();
            }else{
                Log::driver('apple_purchase_log')->debug("NOT FOUND");
                Log::driver('apple_purchase_log')->debug("[$original_transaction_id]($transaction_id) --- ($expiry_date)");
            }
        });
    }
}
