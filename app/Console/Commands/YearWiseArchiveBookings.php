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
    protected $description = 'Move failed bookings older than 1 month to backup table year wise';

    /**
     * Execute the command
     */

    public function handle()
    {
        $startTime = microtime(true);

        try {
                Log::info('YearWiseArchiveBookings started at: ' . now());

                // Dynamically find the year with a record, starting from 2022
                $startYear = 2022;
                $maxYear = (int)date('Y') + 1; // look ahead one year from current
                $foundYear = null;
                $bkBookingLastRecord = null;

                for ($year = $startYear; $year <= $maxYear; $year++) {

                    $tableName = $year . '_booking';
                    if (!Schema::hasTable($tableName)) {
                        continue;
                    }

                    $bkBookingLastRecord = DB::table($tableName)
                                            ->select('journey_dt')
                                            ->orderBy('id', 'desc')
                                            ->first();

                    if ($bkBookingLastRecord) {
                        $foundYear = $year;
                        break;
                    }
                }

                if ($bkBookingLastRecord) {
                    $journeyDate = Carbon::parse($bkBookingLastRecord->journey_dt)->addDay();
                    // $journeyDate = Carbon::parse('2022-12-31')->addDay(); // Testing purpose
                    $journeyDate = $journeyDate->format('Y-m-d');
                } else {
                    // fallback: use the start year and a default date
                    $journeyDate = $startYear . '-04-15';
                }

                $currentYear = date('Y', strtotime($journeyDate));

                Log::info("Archiving year {$currentYear} started");

                // Dynamic table names
                $bookingTable          = "{$currentYear}_booking";
                $bookingDetailTable    = "{$currentYear}_booking_detail";
                $customerPaymentTable  = "{$currentYear}_customer_payment";
                $bookingSequenceTable  = "{$currentYear}_booking_sequence";

                if (
                    !Schema::hasTable($bookingTable) ||
                    !Schema::hasTable($bookingDetailTable) ||
                    !Schema::hasTable($customerPaymentTable) ||
                    !Schema::hasTable($bookingSequenceTable)
                ) {
                    Log::error("Archive tables missing for year {$currentYear}");
                    return;
                }
             
                $oldBookings = Booking::whereIn('status', [1, 2])
                                        ->whereDate('journey_dt', $journeyDate)
                                        ->selectRaw('*, id AS booking_id')
                                        ->get()
                                        ->makeHidden(['id'])
                                        ->toArray();

                $oldBookings = collect($oldBookings)->map(function ($booking) {
                    if (isset($booking['created_at'])) {
                        $booking['created_at'] = Carbon::parse($booking['created_at'])->format('Y-m-d H:i:s');
                    }

                    if (isset($booking['updated_at'])) {
                        $booking['updated_at'] = Carbon::parse($booking['updated_at'])->format('Y-m-d H:i:s');
                    }

                    return $booking;
                })->toArray();

                if (empty($oldBookings)) {
                    Log::info("No bookings for {$journeyDate} to archive");
                }

                $bookingIds = collect($oldBookings)->pluck('booking_id')->toArray();

                Log::info('Total Bookings', ['count' => count($bookingIds)]);

                // ---------------------------------
                // Fetch related tables
                // ---------------------------------
                $oldCustomerPayments = CustomerPayment::whereIn('booking_id', $bookingIds)
                                                        ->selectRaw('*, id AS customer_payment_id')
                                                        ->get()
                                                        ->makeHidden(['id'])
                                                        ->toArray();

                $oldCustomerPayments = collect($oldCustomerPayments)->map(function ($bookingPay) {
                    if (isset($bookingPay['created_at'])) {
                        $bookingPay['created_at'] = Carbon::parse($bookingPay['created_at'])->format('Y-m-d H:i:s');
                    }

                    if (isset($bookingPay['updated_at'])) {
                        $bookingPay['updated_at'] = Carbon::parse($bookingPay['updated_at'])->format('Y-m-d H:i:s');
                    }

                    return $bookingPay;
                })->toArray();

                $oldBookingDetail = BookingDetail::whereIn('booking_id', $bookingIds)
                                                    ->selectRaw('*, id AS booking_detail_id')
                                                    ->get()
                                                    ->makeHidden(['id'])
                                                    ->toArray();

                $oldBookingDetail = collect($oldBookingDetail)->map(function ($bookingDtls) {
                    if (isset($bookingDtls['created_at'])) {
                        $bookingDtls['created_at'] = Carbon::parse($bookingDtls['created_at'])->format('Y-m-d H:i:s');
                    }

                    if (isset($bookingDtls['updated_at'])) {
                        $bookingDtls['updated_at'] = Carbon::parse($bookingDtls['updated_at'])->format('Y-m-d H:i:s');
                    }

                    return $bookingDtls;
                })->toArray();

                $oldBookingSequence = BookingSequence::whereIn('booking_id', $bookingIds)
                    ->selectRaw('*, id AS booking_sequence_id')
                    ->get()
                    ->makeHidden(['id'])
                    ->toArray();

                $oldBookingSequence = collect($oldBookingSequence)->map(function ($bookingSeq) {
                    if (isset($bookingSeq['created_at'])) {
                        $bookingSeq['created_at'] = Carbon::parse($bookingSeq['created_at'])->format('Y-m-d H:i:s');
                    }

                    if (isset($bookingSeq['updated_at'])) {
                        $bookingSeq['updated_at'] = Carbon::parse($bookingSeq['updated_at'])->format('Y-m-d H:i:s');
                    }

                    return $bookingSeq;
                })->toArray();
               
                DB::beginTransaction();

                try {

                    // Delete related SMS (main DB)
                    DB::table('manage_sms')->whereIn('booking_id', $bookingIds)->delete();

                    // Insert into YEAR tables (ARCHIVE DB)
                    DB::table($bookingTable)->insert($oldBookings);
                    DB::table($bookingDetailTable)->insert($oldBookingDetail);
                    DB::table($customerPaymentTable)->insert($oldCustomerPayments);
                    DB::table($bookingSequenceTable)->insert($oldBookingSequence);

                    // Delete from main tables
                    DB::table('booking_sequence')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking_detail')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('customer_payment')->whereIn('booking_id', $bookingIds)->delete();
                    DB::table('booking')->whereIn('id', $bookingIds)->delete();

                    DB::commit();

                    Log::info("Archived {$journeyDate} ({$currentYear}) successfully");

                } catch (\Throwable $e) {

                    DB::rollBack();
                    throw $e;
                }

                sleep(0.5); // throttle

                $this->info("Archived date {$journeyDate} for year {$currentYear}. Run next time for the next date.");
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
