<?php

namespace App\Console\Commands;

use App\Models\BusOwnerFare;
use App\Models\OwnerFare;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveOwnerFare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fare:archive-owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old Owner Fare records into archive table';

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
            Log::info('Archive Owner Fare started at: ' . now());

            // ---------------------------------
            // Get last processed date
            // ---------------------------------
            $getLastRecord = DB::table('bk_owner_fare')
                ->select('date')
                ->orderBy('id', 'desc')
                ->first();

            if ($getLastRecord) {
                $cutoffDate = Carbon::parse($getLastRecord->date)->addDay();
            } else {
                $cutoffDate = Carbon::parse('2022-04-15');
            }

            // ---------------------------------
            // BEGIN TRANSACTION
            // ---------------------------------
            DB::beginTransaction();

            // ---------------------------------
            // Find next date having Owner Fare
            // ---------------------------------
            $nextArchiveDate = OwnerFare::whereDate('date', '>=', $cutoffDate->toDateString())
                ->orderBy('date')->value('date');

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
            // Fetch Owner Fare for that date
            // ---------------------------------
            $archiveOwnerFare = OwnerFare::whereDate('date', $cutoffDate)
                ->selectRaw('*, id AS owner_fare_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $archiveOwnerFare = collect($archiveOwnerFare)->map(function ($nAD) {
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
            $_ids = collect($archiveOwnerFare)
                ->pluck('owner_fare_id')
                ->filter()
                ->toArray();

            Log::info('Total Archive Data', ['count' => count($_ids)]);
            Log::info('Archive Data found for date: ' . $cutoffDate);

            // ---------------------------
            // Fetch dependent tables
            // ---------------------------
            $bofArr = BusOwnerFare::whereIn('owner_fare_id', $_ids)
                ->selectRaw('*, id AS bus_owner_fare_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $bofArr = collect($bofArr)->map(function ($bCDA) {
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
            DB::table('bk_owner_fare')->insert($archiveOwnerFare);
            DB::table('bk_bus_owner_fare')->insert($bofArr);

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            DB::table('bus_owner_fare')->whereIn('owner_fare_id', $_ids)->delete();
            DB::table('owner_fare')->whereIn('id', $_ids)->delete();

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

            Log::error('Archive Owner Fare Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');
        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('Archive Owner Fare ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}
