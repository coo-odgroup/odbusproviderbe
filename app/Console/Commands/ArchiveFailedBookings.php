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


            $bkBookingLastRecord = DB::table('bk_booking')
                ->select('journey_dt')
                ->orderBy('id', 'desc')
                ->first();

            if ($bkBookingLastRecord) {
                $cutoffDate = Carbon::parse($bkBookingLastRecord->journey_dt)->addDay();
            } else {
                $cutoffDate = '2022-04-15';
            }

            DB::beginTransaction();

            $nextJourneyDate = Booking::whereIn('status', [0, 4])
                ->whereDate('journey_dt', '=', $cutoffDate)
                ->orderBy('journey_dt')
                ->value('journey_dt');

            if (!$nextJourneyDate) {
                DB::rollBack();
                $this->info('No failed bookings found');
                return Command::SUCCESS;
            }

            $cutoffDate = Carbon::parse($nextJourneyDate)->toDateString();

            $failedBookings = Booking::whereIn('status', [0, 4])
                ->where('journey_dt', $cutoffDate)
                ->selectRaw('*, id AS booking_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedBookings = collect($failedBookings)->map(function ($booking) {
                if (isset($booking['created_at'])) {
                    $booking['created_at'] = Carbon::parse($booking['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($booking['updated_at'])) {
                    $booking['updated_at'] = Carbon::parse($booking['updated_at'])->format('Y-m-d H:i:s');
                }

                return $booking;
            })->toArray();

            $bookingIds = collect($failedBookings)
                ->pluck('booking_id')
                ->filter()
                ->toArray();

            // Log::info($bookingIds); exit;
            Log::info('Total Bookings', ['count' => count($bookingIds)]);
            Log::info('Failed bookings found for date: ' . $cutoffDate);

            $failedCustomerPayments = CustomerPayment::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS customer_payment_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedCustomerPayments = collect($failedCustomerPayments)->map(function ($customerPayment) {
                if (isset($customerPayment['created_at'])) {
                    $customerPayment['created_at'] = Carbon::parse($customerPayment['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($customerPayment['updated_at'])) {
                    $customerPayment['updated_at'] = Carbon::parse($customerPayment['updated_at'])->format('Y-m-d H:i:s');
                }

                return $customerPayment;
            })->toArray();

            $failedBookingDetail = BookingDetail::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS booking_detail_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedBookingDetail = collect($failedBookingDetail)->map(function ($bookingDetails) {
                if (isset($bookingDetails['created_at'])) {
                    $bookingDetails['created_at'] = Carbon::parse($bookingDetails['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($bookingDetails['updated_at'])) {
                    $bookingDetails['updated_at'] = Carbon::parse($bookingDetails['updated_at'])->format('Y-m-d H:i:s');
                }

                return $bookingDetails;
            })->toArray();

            $failedBookingSequence = BookingSequence::whereIn('booking_id', $bookingIds)
                ->selectRaw('*, id AS booking_sequence_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $failedBookingSequence = collect($failedBookingSequence)->map(function ($bookingSeq) {
                if (isset($bookingSeq['created_at'])) {
                    $bookingSeq['created_at'] = Carbon::parse($bookingSeq['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($bookingSeq['updated_at'])) {
                    $bookingSeq['updated_at'] = Carbon::parse($bookingSeq['updated_at'])->format('Y-m-d H:i:s');
                }

                return $bookingSeq;
            })->toArray();

            // ---------------------------
            // Delete related SMS
            // ---------------------------
            DB::table('manage_sms')->whereIn('booking_id', $bookingIds)->delete();

            // ---------------------------
            // Insert into backup tables
            // ---------------------------
            // DB::table('bk_booking')->insert($failedBookings);
            collect($failedBookings)
                ->chunk(200) // 100–500 is usually safe
                ->each(function ($chunk) {
                    DB::table('bk_booking')->insert($chunk->toArray());
                });

            // DB::table('bk_booking_detail')->insert($failedBookingDetail);
            collect($failedBookingDetail)
                ->chunk(200)
                ->each(function ($chunk) {
                    DB::table('bk_booking_detail')->insert($chunk->toArray());
                });
            // DB::table('bk_customer_payment')->insert($failedCustomerPayments);
            collect($failedCustomerPayments)
                ->chunk(200)
                ->each(function ($chunk) {
                    DB::table('bk_customer_payment')->insert($chunk->toArray());
                });
            // DB::table('bk_booking_sequence')->insert($failedBookingSequence);
            collect($failedBookingSequence)
                ->chunk(200)
                ->each(function ($chunk) {
                    DB::table('bk_booking_sequence')->insert($chunk->toArray());
                });

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            // DB::table('booking_sequence')->whereIn('booking_id', $bookingIds)->delete();
            // DB::table('booking_detail')->whereIn('booking_id', $bookingIds)->delete();
            // DB::table('customer_payment')->whereIn('booking_id', $bookingIds)->delete();
            // DB::table('booking')->whereIn('id', $bookingIds)->delete();

            collect($bookingIds)
                ->chunk(200)
                ->each(function ($chunk) {

                    DB::table('booking_sequence')
                        ->whereIn('booking_id', $chunk)
                        ->delete();

                    DB::table('booking_detail')
                        ->whereIn('booking_id', $chunk)
                        ->delete();

                    DB::table('customer_payment')
                        ->whereIn('booking_id', $chunk)
                        ->delete();

                    DB::table('booking')
                        ->whereIn('id', $chunk)
                        ->delete();
                });


            // ---------------------------
            // COMMIT TRANSACTION
            // ---------------------------
            DB::commit();
            DB::disconnect();
            sleep(0.5);

            $this->info('Archiving completed successfully');
        } catch (\Throwable $e) {

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
