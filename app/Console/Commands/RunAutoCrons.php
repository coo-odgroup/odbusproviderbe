<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Cron;
use App\Models\CronLog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunAutoCrons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crons:run-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all active auto crons';

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
        $crons = Cron::where('run_type', 'auto')
            ->where('is_active', 1)
            ->get();

        foreach ($crons as $cron) {
            $this->runCron($cron, 'auto');
        }
    }

    private function runCron($cron, $type)
    {
        $log = CronLog::create([
            'cron_id' => $cron->id,
            'run_type' => $type,
            'status' => 'running',
            'started_at' => now()
        ]);

        $start = microtime(true);

        try {
            Artisan::call($cron->command);

            $log->update([
                'status' => 'success',
                'output' => Artisan::output()
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
        }

        $end = microtime(true);

        $log->update([
            'ended_at' => now(),
            'execution_time_ms' => ($end - $start) * 1000
        ]);

        $cron->update([
            'last_run_at' => now()
        ]);
    }
}
