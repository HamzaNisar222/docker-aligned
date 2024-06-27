<?php

namespace App\Console;

use App\Jobs\RemoveExpiredTokens;
use App\Jobs\SendPendingPaymentReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule job for removing expired tokens
        $schedule->job(new RemoveExpiredTokens)->everyMinute();

        // Schedule job for sending pending payment reminders to clients
        $schedule->job(new SendPendingPaymentReminder)->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
