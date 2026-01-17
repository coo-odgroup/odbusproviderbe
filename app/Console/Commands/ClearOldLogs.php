<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClearOldLogs extends Command
{
    /**
     * Command name
     */
    protected $signature = 'logs:clear-old {--limit=10}';

    /**
     * Command description
     */
    protected $description = 'Delete old log records in batches (default 10 per run)';

    public function handle()
    {
        $startTime = microtime(true);

        try {
            Log::info('Log cleanup started', ['time' => now()]);

            $limit = (int) $this->option('limit') ?: 10;
            $cutoffDate = Carbon::now()->subMonths(2);

            /**
             * table => date_column
             */
            $tables = [
                'payment_webhook' => 'date',
                'api_log'         => 'created_at',
                'sms_log'         => 'created_on',
            ];

            foreach ($tables as $table => $dateColumn) {

                DB::beginTransaction();

                // Fetch IDs first (safe for MySQL)
                $ids = DB::table($table)
                    ->where($dateColumn, '<', $cutoffDate)
                    ->orderBy('id')
                    ->limit($limit)
                    ->pluck('id');

                if ($ids->isEmpty()) {
                    DB::rollBack();
                    $this->info("{$table}: No old records found");
                    continue;
                }

                $deleted = DB::table($table)
                    ->whereIn('id', $ids)
                    ->delete();

                DB::commit();

                $this->info("{$table}: {$deleted} records deleted");

                Log::info("Log cleanup executed", [
                    'table'     => $table,
                    'deleted'   => $deleted,
                    'cutoff'    => $cutoffDate->toDateTimeString(),
                    'batchSize' => $limit,
                ]);
            }

            $this->info('Batch cleanup completed successfully');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Log cleanup failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            $this->error('Cleanup failed. Check logs for details.');

        } finally {

            $executionTime = round(microtime(true) - $startTime, 2);

            Log::info('Log cleanup ended', [
                'time'           => now(),
                'execution_time' => "{$executionTime}s",
            ]);

            $this->info("Execution completed in {$executionTime} seconds");
        }

        return Command::SUCCESS;
    }
}