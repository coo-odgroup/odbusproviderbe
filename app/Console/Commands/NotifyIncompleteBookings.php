<?php

namespace App\Console\Commands;

use App\Services\Msg91Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotifyIncompleteBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:incomplete-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify about incomplete bookings';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $msg91Service;

    public function __construct(Msg91Service $msg91Service)
    {
        parent::__construct();

        $this->msg91Service = $msg91Service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();

        // 1. Find incomplete payment attempts
        $incompletePayments = DB::table('customer_payment')
            ->where('payment_done', 0)
            ->where('updated_at', '>=', $now->copy()->subMinutes(5))
            ->where('updated_at', '<=', $now)
            ->get();

        log::info('Found ' . count($incompletePayments) . ' incomplete payments');

        return Command::SUCCESS;

        foreach ($incompletePayments as $payment) {

            // 2. Find another booking by the same customer
            $newBooking = DB::table('booking')
                ->where('user_id', $payment->user_id)
                ->where('created_at', '>', $payment->updated_at)
                ->where(
                    'created_at',
                    '<=',
                    date(
                        'Y-m-d H:i:s',
                        strtotime($payment->updated_at . ' +5 minutes')
                    )
                )
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$newBooking) {
                continue;
            }

            // 3. Prevent duplicate notification
            $alreadyNotified = DB::table('notification_observation')
                ->where('event', 'BOOKING_AFTER_INCOMPLETE_PAYMENT')
                ->where('reference_id', $newBooking->booking_id)
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            // 4. Create processing record
            $observationId = DB::table('notification_observation')
                ->insertGetId([
                    'event'        => 'BOOKING_AFTER_INCOMPLETE_PAYMENT',
                    'reference_id' => $newBooking->booking_id,
                    'status'       => 'PROCESSING',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);

            try {

                // 5. Send notification
                // $this->msg91Service->sendNotification([
                //     'user_id'    => $payment->user_id,
                //     'booking_id' => $newBooking->booking_id,
                // ]);

                // 6. Mark notification sent
                DB::table('notification_observation')
                    ->where('id', $observationId)
                    ->update([
                        'status'     => 'SENT',
                        'updated_at' => now(),
                    ]);

                $this->info(
                    "Notification sent for booking {$newBooking->booking_id}"
                );
            } catch (\Exception $e) {

                DB::table('notification_observation')
                    ->where('id', $observationId)
                    ->update([
                        'status'     => 'FAILED',
                        'updated_at' => now(),
                    ]);

                $this->error($e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
