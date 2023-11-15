<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * @codeCoverageIgnore
     */
    protected function schedule(Schedule $schedule): void
    {
        // TODO: Write test here
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
    * @codeCoverageIgnore
     */
    protected function commands(): void
    {
        // TODO: Write test here
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
