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
        \App\Console\Commands\UpdateMinPriceForBus::class,
        \App\Console\Commands\ArchiveFailedBookings::class,
        \App\Console\Commands\YearWiseArchiveBookings::class,
        \App\Console\Commands\ArchiveBusCancellation::class,
        \App\Console\Commands\ArchiveDaywiseBookingSeized::class,
        \App\Console\Commands\ArchiveFestivalFare::class,
        \App\Console\Commands\ArchiveOwnerFare::class,
        \App\Console\Commands\ArchiveSpecialFare::class,
        \App\Console\Commands\ClearOldLogs::class,
        \App\Console\Commands\RunAutoCrons::class,
        \App\Console\Commands\PhonePeRefundStatus::class,
        \App\Console\Commands\LowWalletBalanceNotification::class,
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

        $schedule->command('phonepe:refund-status')->everyFiveMinutes();


        $crons = Cron::with('cron_frequencies')
            ->where('run_type', 'auto')
            ->where('is_active', 1)
            ->get();


        $schedule->command('wallet:low-balance')
            ->everyFifteenMinutes();
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
