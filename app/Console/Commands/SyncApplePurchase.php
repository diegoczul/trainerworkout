<?php

namespace App\Console\Commands;

use App\Models\AppleNotification;
use App\Models\MembershipsUsers;
use App\Models\UserApplePurchaseTransaction;
use App\Models\Users;
use http\Client\Curl\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
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
        $AppleNotificationId = AppleNotification::selectRaw('MAX(id) as max_id')
            ->where('is_verified',0)
            ->groupBy('original_transaction_id')
            ->pluck('max_id');

        if (!empty($AppleNotificationId)){
            $AppleNotification = AppleNotification::whereIn('id',$AppleNotificationId)->get()->toArray();

            foreach($AppleNotification as $notification) {
                $original_transaction_id = $notification['original_transaction_id'];
                $transaction_id = $notification['transaction_id'];
                $expiry_date = $notification['expiry_date'];
                $MembershipsUsers = MembershipsUsers::where([
                    'apple_original_transaction_id' => $original_transaction_id,
                    'deleted_at' => null
                ])->count();
                if ($MembershipsUsers > 0){
                    if ($notification['notification_type'] == 'EXPIRED'){
                        $users = MembershipsUsers::where([
                            'apple_original_transaction_id' => $original_transaction_id,
                            'deleted_at' => null
                        ])->pluck('userId');

                        // REMOVE ALL OLD MEMBERSHIPS
                        MembershipsUsers::where([
                            'apple_original_transaction_id' => $original_transaction_id,
                            'deleted_at' => null
                        ])->delete();

                        // ADD TRIAL MEMBERSHIP
                        foreach ($users as $user){
                            $u = Users::find($user);
                            $u->updateToMembership(Config::get('constants.freeTrialMembershipId'));
                        }
                    }else{
                        // UPDATE MEMBERSHIP EXPIRY
                        MembershipsUsers::where([
                            'apple_original_transaction_id' => $original_transaction_id,
                            'deleted_at' => null
                        ])->update(['expiry' => Carbon::parse($expiry_date)]);
                    }

                    // UPDATE USER TRANSACTION STATUS
                    UserApplePurchaseTransaction::where([
                        'original_transaction_id' => $original_transaction_id,
                        'transaction_id' => $transaction_id,
                    ])->update(['is_verified' => 1]);

                    // UPDATE NOTIFICATION VERIFICATION STATUS
                    AppleNotification::where('id','<=',$notification['id'])
                        ->where('original_transaction_id',$original_transaction_id)
                        ->update(['is_verified' => 1]);
                }else{
                    // UPDATE USER TRANSACTION STATUS
                    UserApplePurchaseTransaction::where([
                        'original_transaction_id' => $original_transaction_id,
                        'transaction_id' => $transaction_id,
                    ])->update(['is_verified' => 1]);

                    // UPDATE NOTIFICATION VERIFICATION STATUS
                    AppleNotification::where('id','<=',$notification['id'])
                        ->where('original_transaction_id',$original_transaction_id)
                        ->update(['is_verified' => 2]);

                    Log::driver('apple_purchase_log')->debug("NOT FOUND");
                    Log::driver('apple_purchase_log')->debug("[$original_transaction_id]($transaction_id) --- ($expiry_date)");
                }
            };
        }

    }
}
