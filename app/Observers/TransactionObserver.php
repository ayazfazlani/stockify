<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\TeamMetricsService;

class TransactionObserver
{
    protected $metricsService;

    public function __construct(TeamMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function created(Transaction $transaction)
    {
        $team = $transaction->team;
        
        // Record transaction metrics
        $this->metricsService->recordMetric($team, 'transactions', 1, [
            'type' => $transaction->type,
            'amount' => $transaction->amount,
        ]);

        // Update transaction count quota if applicable
        if ($team->subscription_plan) {
            $this->metricsService->incrementQuota($team, 'transactions', 1);
        }
    }
}