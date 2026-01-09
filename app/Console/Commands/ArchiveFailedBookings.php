<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\CustomerPayment;
use App\Models\BookingDetail;
use App\Models\BookingSequence;

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

            // ---------------------------------
            // Get last processed journey date
            // ---------------------------------
            $bkBookingLastRecord = DB::table('bk_booking')
                ->select('journey_dt')
                ->orderBy('id', 'desc')
                ->first();

            if ($bkBookingLastRecord) {
                $cutoffDate = Carbon::parse($bkBookingLastRecord->journey_dt)->addDay();
            } else {
                $cutoffDate = Carbon::parse('2022-04-15');
            }

            // ---------------------------------
            // BEGIN TRANSACTION
            // ---------------------------------
            DB::beginTransaction();

            // ---------------------------------
            // Find next date having failed bookings
            // ---------------------------------
            $nextJourneyDate = Booking::whereIn('status', [0, 4])
                ->whereDate('journey_dt', '>=', $cutoffDate->toDateString())
                ->orderBy('journey_dt')
                ->value('journey_dt');

            if (!$nextJourneyDate) {
                DB::rollBack();
                $this->info('No failed bookings found');
                return Command::SUCCESS;
            }

            $cutoffDate = Carbon::parse($nextJourneyDate)->toDateString();

            // ---------------------------------
            // Fetch failed bookings for that date
            // ---------------------------------
            $failedBookings = Booking::whereIn('status', [0, 4])
                ->whereDate('journey_dt', $cutoffDate)
                ->selectRaw('*, id AS booking_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            // ---------------------------------
            // Extract booking IDs
            // ---------------------------------
            $bookingIds = collect($failedBookings)
                ->pluck('booking_id')
                ->filter()
                ->toArray();

            Log::info('Total Bookings', ['count' => count($bookingIds)]);
            Log::info('Failed bookings found for date: ' . $cutoffDate);
            // return;

            // ---------------------------
            // Fetch dependent tables
            // ---------------------------
            $failedCustomerPayments = CustomerPayment::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS customer_payment_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedBookingDetail = BookingDetail::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS booking_detail_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedBookingSequence = BookingSequence::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS booking_sequence_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            // ---------------------------
            // Delete related SMS
            // ---------------------------
            DB::table('manage_sms')->whereIn('booking_id', $bookingIds)->delete();

            // ---------------------------
            // Insert into backup tables
            // ---------------------------
            DB::table('bk_booking')->insert($failedBookings);
            DB::table('bk_booking_detail')->insert($failedBookingDetail);
            DB::table('bk_customer_payment')->insert($failedCustomerPayments);
            DB::table('bk_booking_sequence')->insert($failedBookingSequence);

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            DB::table('booking_sequence')->whereIn('booking_id', $bookingIds)->delete();
            DB::table('booking_detail')->whereIn('booking_id', $bookingIds)->delete();
            DB::table('customer_payment')->whereIn('booking_id', $bookingIds)->delete();
            DB::table('booking')->whereIn('id', $bookingIds)->delete();

            // ---------------------------
            // COMMIT TRANSACTION
            // ---------------------------
            DB::commit();
            DB::disconnect();
            sleep(0.5);

            $this->info('Archiving completed successfully');

        } catch (\Throwable $e) {

            // ---------------------------
            // ROLLBACK ON ERROR
            // ---------------------------
            DB::rollBack();

            Log::error('ArchiveFailedBookings Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
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