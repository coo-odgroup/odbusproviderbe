<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AgentWallet;
use App\Models\Notification;
use App\Models\UserNotification;
use Carbon\Carbon;
use DB;

class LowWalletBalanceNotification extends Command
{
    protected $signature = 'wallet:low-balance';

    protected $description = 'Send notification to agents having low wallet balance';

 public function handle()
{

    // GET LATEST ACTIVE WALLET ENTRY OF EACH AGENT
    $wallets = AgentWallet::select(
            'user_id',
            DB::raw('MAX(id) as latest_id')
        )
        ->where('status', 1)
        ->whereNotNull('user_id')
        ->groupBy('user_id')
        ->get();


    foreach ($wallets as $wallet) {

        $walletData = AgentWallet::find($wallet->latest_id);

        if (!$walletData) {
            continue;
        }


        // CHECK LOW BALANCE
        if ((float)$walletData->balance < 100) {

            // CHECK IF NOTIFICATION ALREADY SENT IN LAST 48 HOURS
            $alreadySent = Notification::where(
                    'notification_heading',
                    'Low Wallet Balance'
                )
                ->whereHas('userNotification', function ($q) use ($walletData) {

                    $q->where('user_id', $walletData->user_id);

                })
                ->where(
                    'created_at',
                    '>=',
                    Carbon::now()->subHours(48)
                )
                ->exists();


            if (!$alreadySent) {

                // CREATE NOTIFICATION
                $notification = new Notification();
                $notification->notification_heading ='Low Wallet Balance';
                $notification->notification_details ='Your wallet balance is below ₹100. Please recharge your wallet to continue booking services without interruption.';
                $notification->created_by = 'System';
                $notification->status = 1;
                $notification->save();

                // MAP NOTIFICATION TO USER
                $userNotification = new UserNotification();
                $userNotification->notification_id =$notification->id;
                $userNotification->user_id =$walletData->user_id;
                $userNotification->created_by = 'System';
                $userNotification->status = 1;
                $userNotification->save();


                $this->info(
                    'Low balance notification sent to User ID : ' .
                    $walletData->user_id
                );
            }
        }
    }

    return 0;
}
}
