<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('workout:export-article')->dailyAt('09:00'); // 2AM server time
        $schedule->command('plans:sync-cancellations')->everyTenMinutes();
        $schedule->command('sync:stripe-transactions')->dailyAt('02:00'); // 2AM server time
        $schedule->command('app:release-frozen-earnings')->daily();
        $schedule->command('sync:apple-purchase')->everyMinute();
        $schedule->call(function () {
            Log::info('Cron is working');
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
