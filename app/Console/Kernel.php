<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        Commands\SessionCron::class,
        Commands\SyncMailboxes::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('session:cron')
            ->dailyAt('18:00')
            ->timezone('America/New_York');

        $schedule->command('app:send-template-email')
            ->everyFifteenMinutes()
            ->timezone('America/New_York');

        // Sync all active mailboxes every 30 minutes
        $schedule->command('mailbox:sync')
            ->everyFiveMinutes()
            ->runInBackground()
            ->withoutOverlapping()
            ->timezone('America/New_York');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
