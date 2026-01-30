<?php

namespace App\Console\Commands;

use App\Models\BookingSeized;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveDaywiseBookingSeized extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:archive-daywise-seized';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive Daywise Booking Seized Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startTime = microtime(true);

        try {
            Log::info('ArchiveDaywiseBookingSeized started at: ' . now());

            // ---------------------------------
            // Get last processed date
            // ---------------------------------
            $bk_daywise_booking_seized_last_rec = DB::table('bk_daywise_booking_seized')
                ->select('seized_date')
                ->orderBy('id', 'desc')
                ->first();

            if ($bk_daywise_booking_seized_last_rec) {
                $cutoffDate = Carbon::parse($bk_daywise_booking_seized_last_rec->seized_date)->addDay();
            } else {
                $cutoffDate = Carbon::parse('2022-04-15');
            }

            // ---------------------------------
            // BEGIN TRANSACTION
            // ---------------------------------
            DB::beginTransaction();

            // ---------------------------------
            // Find next date having ArchiveBusCancellation
            // ---------------------------------
            $nextArchiveDate = BookingSeized::whereDate('seized_date', '>=', $cutoffDate->toDateString())
                ->orderBy('seized_date')->value('seized_date');

            if (!$nextArchiveDate) {
                DB::rollBack();
                $this->info('No records found');
                return Command::SUCCESS;
            }

            $today = now();
            $oneMonthBefore = $today->copy()->subMonthNoOverflow();

            $nextArchiveDate = Carbon::parse($nextArchiveDate);

            if ($nextArchiveDate->between($oneMonthBefore, $today)) {
                $this->info("Stopping archive. {$nextArchiveDate->toDateString()} is within last 1 month.");
                DB::rollBack();
                return Command::SUCCESS;
            }

            $cutoffDate = Carbon::parse($nextArchiveDate)->toDateString();

            // ---------------------------------
            // Fetch ArchiveBusCancellation for that date
            // ---------------------------------
            $ArchiveBookingSeized = BookingSeized::whereDate('seized_date', $cutoffDate)
                ->selectRaw('*, id AS daywise_booking_seized_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $ArchiveBookingSeized = collect($ArchiveBookingSeized)->map(function ($nAD) {
                if (isset($nAD['created_at'])) {
                    $nAD['created_at'] = Carbon::parse($nAD['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($nAD['updated_at'])) {
                    $nAD['updated_at'] = Carbon::parse($nAD['updated_at'])->format('Y-m-d H:i:s');
                }

                return $nAD;
            })->toArray();

            // ---------------------------------
            // Extract booking IDs
            // ---------------------------------
            $collected_ids = collect($ArchiveBookingSeized)
                ->pluck('daywise_booking_seized_id')
                ->filter()
                ->toArray();

            Log::info('Total ArchiveBusCancellation', ['count' => count($collected_ids)]);
            Log::info('ArchiveBusCancellation found for date: ' . $cutoffDate);

            // ---------------------------
            // Insert into backup tables
            // ---------------------------
            DB::table('bk_daywise_booking_seized')->insert($ArchiveBookingSeized);

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            DB::table('daywise_booking_seized')->whereIn('id', $collected_ids)->delete();

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

            Log::error('ArchiveDaywiseBookingSeized Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');
        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('ArchiveDaywiseBookingSeized ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}
