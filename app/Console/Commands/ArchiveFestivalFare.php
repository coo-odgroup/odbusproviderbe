<?php

namespace App\Console\Commands;

use App\Models\BusFestivalFare;
use App\Models\FestivalFare;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveFestivalFare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fare:archive-festival';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old Festival Fare records to archive table';

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
            Log::info('Archive Festival Fare started at: ' . now());

            // ---------------------------------
            // Get last processed date
            // ---------------------------------
            $getLastRecord = DB::table('bk_festival_fare')
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
            // Find next date having Festival Fare
            // ---------------------------------
            $nextArchiveDate = FestivalFare::whereDate('date', '>=', $cutoffDate->toDateString())
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
            // Fetch Festival Fare for that date
            // ---------------------------------
            $archiveFestivalFare = FestivalFare::whereDate('date', $cutoffDate)
                ->selectRaw('*, id AS festival_fare_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $archiveFestivalFare = collect($archiveFestivalFare)->map(function ($nAD) {
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
            $_ids = collect($archiveFestivalFare)
                ->pluck('festival_fare_id')
                ->filter()
                ->toArray();

            Log::info('Total Archive Data', ['count' => count($_ids)]);
            Log::info('Archive Data found for date: ' . $cutoffDate);

            // ---------------------------
            // Fetch dependent tables
            // ---------------------------
            $bffArr = BusFestivalFare::whereIn('festival_fare_id', $_ids)
                ->selectRaw('*, id AS bus_festival_fare_id')
                ->get()
                ->makeHidden(['id'])
                ->toArray();

            $bffArr = collect($bffArr)->map(function ($bffA) {
                if (isset($bffA['created_at'])) {
                    $bffA['created_at'] = Carbon::parse($bffA['created_at'])->format('Y-m-d H:i:s');
                }

                if (isset($bffA['updated_at'])) {
                    $bffA['updated_at'] = Carbon::parse($bffA['updated_at'])->format('Y-m-d H:i:s');
                }

                return $bffA;
            })->toArray();

            // ---------------------------
            // Insert into backup tables
            // ---------------------------
            DB::table('bk_festival_fare')->insert($archiveFestivalFare);
            DB::table('bk_bus_festival_fare')->insert($bffArr);

            // ---------------------------
            // Delete from main tables
            // ---------------------------
            DB::table('bus_festival_fare')->whereIn('festival_fare_id', $_ids)->delete();
            DB::table('festival_fare')->whereIn('id', $_ids)->delete();

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

            Log::error('Archive Festival Fare Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Archiving failed. Check logs for details.');
        } finally {

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('Archive Festival Fare ended at: ' . now());
            Log::info("Total execution time: {$executionTime} seconds");

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}
