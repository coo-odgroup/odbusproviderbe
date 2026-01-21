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
use Illuminate\Support\Facades\Schema;

class YearWiseArchiveBookings extends Command
{
    /**
     * Command name
     */
    protected $signature = 'booking:year-wise-archive';

    /**
     * Command description
     */
    protected $description = 'Move bookings older than 1 month to backup table year wise';

    /**
     * Execute the command
     */

    public function handle()
    {
        $startTime = microtime(true);

        try {
            Log::info('YearWiseArchiveBookings started at: ' . now());

            // ---------------------------------
            // Config: never archive before this year
            // ---------------------------------
            $minArchiveYear = 2022;

            // ---------------------------------
            // Get latest journey year
            // ---------------------------------
            $latestJourney = Booking::orderBy('journey_dt', 'desc')->value('journey_dt');

            if (!$latestJourney) {
                $this->info('No bookings found');
                return Command::SUCCESS;
            }

            $latestYear = Carbon::parse($latestJourney)->year;
            $archiveTillYear = $latestYear - 2;  // keep current & previous year in main

            Log::info("Latest Year: {$latestYear}, Archive till: {$archiveTillYear}");

            // ---------------------------------
            // Find all years to archive (>= minArchiveYear)
            // ---------------------------------
            $yearsToArchive = Booking::selectRaw('YEAR(journey_dt) as year')
                ->whereYear('journey_dt', '>=', $minArchiveYear)
                ->whereYear('journey_dt', '<=', $archiveTillYear)
                ->distinct()
                ->orderBy('year') // earliest year first
                ->pluck('year')
                ->toArray();

            if (empty($yearsToArchive)) {
                $this->info('No years eligible for archive');
                return Command::SUCCESS;
            }

            // ---------------------------------
            // Loop years in order
            // ---------------------------------
            foreach ($yearsToArchive as $year) {

                Log::info("Archiving year {$year} started");

                // Dynamic table names
                $bookingTable          = "{$year}_booking";
                $bookingDetailTable    = "{$year}_booking_detail";
                $customerPaymentTable  = "{$year}_customer_payment";
                $bookingSequenceTable  = "{$year}_booking_sequence";

                // Safety: check archive tables exist
                if (
                    !Schema::connection('archive_transaction')->hasTable($bookingTable) ||
                    !Schema::connection('archive_transaction')->hasTable($bookingDetailTable) ||
                    !Schema::connection('archive_transaction')->hasTable($customerPaymentTable) ||
                    !Schema::connection('archive_transaction')->hasTable($bookingSequenceTable)
                ) {
                    Log::error("Archive tables missing for year {$year}");
                    continue;
                }

                // Pick the **oldest pending date** for this year
                $nextJourneyDate = Booking::whereIn('status', [1, 2])
                    ->whereYear('journey_dt', $year)
                    ->orderBy('journey_dt')
                    ->value('journey_dt');

                if (!$nextJourneyDate) {
                    Log::info("No pending dates for year {$year}");
                    continue;
                }

                Log::info("Processing date {$nextJourneyDate} for year {$year}");

                // ---------------------------------
                // Fetch bookings for this date
                // ---------------------------------
                $oldBookings = Booking::whereIn('status', [1, 2])
                    ->whereDate('journey_dt', $nextJourneyDate)
                    ->selectRaw('*, id AS booking_id')
                    ->get()
                    ->makeHidden(['id'])
                    ->toArray();

                if (empty($oldBookings)) {
                    Log::info("No bookings for {$nextJourneyDate}");
                    continue;
                }

                $bookingIds = collect($oldBookings)->pluck('booking_id')->toArray();

                // ---------------------------------
                // Fetch related tables
                // ---------------------------------
                $oldCustomerPayments = CustomerPayment::whereIn('booking_id', $bookingIds)
                    ->selectRaw('*, id AS customer_payment_id')
                    ->get()
                    ->makeHidden(['id'])
                    ->toArray();

                $oldBookingDetail = BookingDetail::whereIn('booking_id', $bookingIds)
                    ->selectRaw('*, id AS booking_detail_id')
                    ->get()
                    ->makeHidden(['id'])
                    ->toArray();

                $oldBookingSequence = BookingSequence::whereIn('booking_id', $bookingIds)
                    ->selectRaw('*, id AS booking_sequence_id')
                    ->get()
                    ->makeHidden(['id'])
                    ->toArray();

                // ---------------------------------
                // Multi-DB Transaction
                // ---------------------------------
                DB::beginTransaction();
                DB::connection('archive_transaction')->beginTransaction();

                try {

                    // Delete related SMS (main DB)
                    DB::table('manage_sms')->whereIn('booking_id', $bookingIds)->delete();

                    // Insert into YEAR tables (ARCHIVE DB)
                    DB::connection('archive_transaction')->table($bookingTable)->insert($oldBookings);
                    DB::connection('archive_transaction')->table($bookingDetailTable)->insert($oldBookingDetail);
                    DB::connection('archive_transaction')->table($customerPaymentTable)->insert($oldCustomerPayments);
                    DB::connection('archive_transaction')->table($bookingSequenceTable)->insert($oldBookingSequence);

                    // Delete from main tables
                    DB::table('booking_sequence')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking_detail')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('customer_payment')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking')->whereIn('id', $bookingIds)->delete();

                    DB::commit();
                    DB::connection('archive_transaction')->commit();

                    Log::info("Archived {$nextJourneyDate} ({$year}) successfully");

                } catch (\Throwable $e) {

                    DB::rollBack();
                    DB::connection('archive_transaction')->rollBack();
                    throw $e;
                }

                sleep(1); // throttle

                // Exit after **archiving one date**, next run will pick next date
                $this->info("Archived date {$nextJourneyDate} for year {$year}. Run next time for the next date.");
                return Command::SUCCESS;
            }

            // If reached here, nothing left to archive
            $this->info('No more dates left to archive');
            return Command::SUCCESS;

        } catch (\Throwable $e) {

            Log::error('YearWiseArchiveBookings Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');

        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('YearWiseArchiveBookings ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}
