<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ProcessSubscriptionRenewals::class,
        Commands\ProcessTeamQuotas::class,
        Commands\SendLowStockAlerts::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process subscription renewals daily at midnight
        $schedule->command('subscriptions:process-renewals')->daily();
        
        // Process store quotas daily at 1 AM
        $schedule->command('stores:process-quotas')->dailyAt('01:00');

        // Queue supplier low-stock alerts daily
        $schedule->command('alerts:send-low-stock')->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}