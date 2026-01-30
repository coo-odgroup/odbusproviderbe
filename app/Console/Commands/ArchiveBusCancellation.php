<?php

namespace App\Console\Commands;

use App\Models\BusCancelled;
use App\Models\BusCancelledDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveBusCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:archive-cancelled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive cancelled bus bookings';

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
            Log::info('ArchiveBusCancellation started at: ' . now());

            // ---------------------------------
            // Get last processed date
            // ---------------------------------
            $bk_bus_cancelled_last_rec = DB::table('bk_bus_cancelled')
                ->select('created_at')
                ->orderBy('id', 'desc')
                ->first();

            if ($bk_bus_cancelled_last_rec) {
                $cutoffDate = Carbon::parse($bk_bus_cancelled_last_rec->created_at)->addDay();
            } else {
                $cutoffDate = Carbon::parse('2022-04-15');
            }

            // ---------------------------------
            // BEGIN TRANSACTION
            // ---------------------------------
            DB::beginTransaction();

            // ---------------------------------
            // Find next date having Bus Cancelled
            // ---------------------------------
            $nextArchiveDate = BusCancelled::whereDate('created_at', '>=', $cutoffDate->toDateString())
                ->orderBy('created_at')->value('created_at');

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
            // Fetch Bus Cancelled for that date
            // ---------------------------------
            $ArchiveBusCancelled = BusCancelled::whereDate('created_at', $cutoffDate)
                ->selectRaw('*, id AS bus_cancelled_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $ArchiveBusCancelled = collect($ArchiveBusCancelled)->map(function ($nAD) {
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
            $bus_cancelled_ids = collect($ArchiveBusCancelled)
                ->pluck('bus_cancelled_id')
                ->filter()
                ->toArray();

            Log::info('Total Bus Cancelled', ['count' => count($bus_cancelled_ids)]);
            Log::info('Bus Cancelled found for date: ' . $cutoffDate);

            // ---------------------------
            // Fetch dependent tables
            // ---------------------------
            $bus_cancelled_date_arr = BusCancelledDate::whereIn('bus_cancelled_id', $bus_cancelled_ids)
                ->selectRaw('*, id AS bus_cancelled_date_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $bus_cancelled_date_arr = collect($bus_cancelled_date_arr)->map(function ($bCDA) {
                if (isset($bCDA['created_at'])) {
                    $bCDA['created_at'] = Carbon::parse($bCDA['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($bCDA['updated_at'])) {
                    $bCDA['updated_at'] = Carbon::parse($bCDA['updated_at'])->format('Y-m-d H:i:s');
                }

                return $bCDA;
            })->toArray();

            // ---------------------------
            // Insert into backup tables
            // ---------------------------
            DB::table('bk_bus_cancelled')->insert($ArchiveBusCancelled);
            DB::table('bk_bus_cancelled_date')->insert($bus_cancelled_date_arr);

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            DB::table('bus_cancelled_date')->whereIn('bus_cancelled_id', $bus_cancelled_ids)->delete();
            DB::table('bus_cancelled')->whereIn('id', $bus_cancelled_ids)->delete();

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

            Log::error('ArchiveBusCancellation Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');
        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('ArchiveBusCancellation ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}
