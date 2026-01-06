<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ArchiveFailedBookings extends Command
{
    /**
     * Command name
     */
    protected $signature = 'booking:archive-failed';

    /**
     * Command description
     */
    protected $description = 'Move failed bookings older than 1 month to backup table';

    /**
     * Execute the command
     */
    public function handle()
    {
        $startTime = microtime(true);

        try {
            Log::info('ArchiveFailedBookings started at: ' . now());

            $bkBookingLastRecord = (array) DB::table('bk_booking')
                ->select('journey_dt')
                ->orderBy('id', 'desc')
                ->first();

            if (!empty($bkBookingLastRecord)) {
                $cutoffDate = Carbon::parse($bkBookingLastRecord['journey_dt'])
                    ->addDay()
                    ->toDateString();
            } else {
                $cutoffDate = "2022-04-15";
            }

            // Log::info($cutoffDate);
            // return;

            DB::transaction(function () use ($cutoffDate) {
                // Before one month particular date
                $failedBookings = DB::table('booking')
                    ->whereIn('status', [0, 4])
                    ->whereDate('journey_dt', '=', $cutoffDate)
                    ->get()
                    ->map(function ($row) {
                        $row = (array) $row;

                        $row['booking_id'] = $row['id'];
                        unset($row['id']);

                        return $row;
                    })
                    ->toArray();

                // $failedBookings = DB::table('booking')
                //     ->selectRaw('*, id AS booking_id')
                //     ->whereIn('status', [0, 4])
                //     ->where('journey_dt', $cutoffDate)
                //     ->get()
                //     ->toArray();

                // Log::info($failedBookings);
                // return;

                if (empty($failedBookings)) {
                    $this->info('No failed bookings found');
                    return;
                }

                $bookingIds = collect($failedBookings)
                    ->pluck('booking_id')
                    ->filter()
                    ->toArray();

                $failedCustomerPayments = DB::table('customer_payment')
                    ->whereIn('booking_id', $bookingIds)
                    ->get()
                    ->map(function ($row) {
                        $row = (array) $row;
                        $row['customer_payment_id'] = $row['id'];
                        unset($row['id']);
                        return $row;
                    })
                    ->toArray();

                $failedBookingDetail = DB::table('booking_detail')
                    ->whereIn('booking_id', $bookingIds)
                    ->get()
                    ->map(function ($row) {
                        $row = (array) $row;
                        $row['booking_detail_id'] = $row['id'];
                        unset($row['id']);
                        return $row;
                    })
                    ->toArray();

                $failedBookingSequence = DB::table('booking_sequence')
                    ->whereIn('booking_id', $bookingIds)
                    ->get()
                    ->map(function ($row) {
                        $row = (array) $row;
                        $row['booking_sequence_id'] = $row['id'];
                        unset($row['id']);
                        return $row;
                    })
                    ->toArray();

                // Log::info($failedCustomerPayments);
                // Log::info($failedBookingDetail);
                // Log::info($failedBookingSequence);
                // return;

                DB::table('manage_sms')->whereIn('booking_id', $bookingIds)->delete();

                // Insert in Backup Table
                DB::table('bk_booking')->insert($failedBookings);
                DB::table('bk_booking_detail')->insert($failedBookingDetail);
                DB::table('bk_customer_payment')->insert($failedCustomerPayments);
                DB::table('bk_booking_sequence')->insert($failedBookingSequence);

                // Log::info('Data inserted');
                // return;

                // Clear Main Table
                if (!empty($bookingIds)) {
                    DB::table('booking_sequence')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking_detail')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('customer_payment')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking')->whereIn('id', $bookingIds)->delete();
                }
            });

        } catch (\Throwable $e) {

            Log::error('ArchiveFailedBookings Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');

        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('ArchiveFailedBookings ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;

    }
}