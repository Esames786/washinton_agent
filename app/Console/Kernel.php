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
            ->timezone('Asia/Karachi');

        $schedule->command('app:send-template-email')
            ->everyFifteenMinutes()
            ->timezone('Asia/Karachi');

        // Sync all active mailboxes every 30 minutes
        $schedule->command('mailbox:sync')
            ->everyFiveMinutes()
            ->runInBackground()
            ->withoutOverlapping()
            ->timezone('Asia/Karachi');

        $schedule->command('ringcentral:sync-history')
            ->dailyAt('10:00')
            ->timezone('Asia/Karachi')
            ->withoutOverlapping();

        $schedule->command('ringcentral:cleanup-old-media --days=30')
            ->dailyAt('02:00')
            ->timezone('Asia/Karachi')
            ->withoutOverlapping();
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
