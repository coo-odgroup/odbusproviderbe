<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Cron;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateMinPriceForBus::class,
        \App\Console\Commands\ArchiveFailedBookings::class,
        \App\Console\Commands\YearWiseArchiveBookings::class,
        \App\Console\Commands\RunAutoCrons::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('booking:archive-failed')->daily();

        $crons = Cron::with('cron_frequencies')
            ->where('run_type', 'auto')
            ->where('is_active', 1)
            ->get();

        // Log::info($crons);
        // return;

        foreach ($crons as $cron) {
            $schedule->command('crons:run-auto')
                ->cron($cron->frequency->expression)
                ->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
