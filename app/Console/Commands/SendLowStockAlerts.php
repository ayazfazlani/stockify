<?php

namespace App\Console\Commands;

use App\Jobs\SendLowStockAlertsJob;
use Illuminate\Console\Command;

class SendLowStockAlerts extends Command
{
    protected $signature = 'alerts:send-low-stock';
    protected $description = 'Queue low stock alerts for suppliers';

    public function handle(): int
    {
        SendLowStockAlertsJob::dispatch();
        $this->info('Low stock alerts job dispatched.');

        return self::SUCCESS;
    }
}
