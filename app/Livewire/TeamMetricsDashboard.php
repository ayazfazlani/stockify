<?php

namespace App\Livewire;

use App\Services\StoreMetricsService;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TeamMetricsDashboard extends Component
{
    public $timeframe = '30';
    public $selectedMetric = 'transactions';
    public $metrics = [];
    public $quotas = [];
    public $store;

    protected $metricsService;

    public function boot(StoreMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function mount()
    {
        $this->store = Auth::user()->currentTeam; // Points to Store model via current_team_id
        $this->loadData();
    }

    public function loadData()
    {
        if (!$this->store) return;

        // Get metrics for the selected timeframe
        $from = now()->subDays((int)$this->timeframe);
        $metrics = $this->metricsService->getStoreMetrics(
            $this->store,
            $this->selectedMetric,
            $from,
            now()
        );

        $this->metrics = $metrics->groupBy(function($metric) {
            return $metric->recorded_at->format('Y-m-d');
        })->map(function($dayMetrics) {
            return $dayMetrics->sum('value');
        })->toArray();

        // Get all quotas
        $this->quotas = $this->metricsService->getAllQuotas($this->store)->map(function($quota) {
            return [
                'name' => $quota->quota_name,
                'used' => $quota->used,
                'limit' => $quota->limit,
                'percentage' => $quota->usage_percentage,
                'remaining' => $quota->remaining,
                'reset_at' => $quota->reset_at ? $quota->reset_at->format('Y-m-d H:i:s') : null,
            ];
        })->toArray();
    }

    public function updatedTimeframe()
    {
        $this->loadData();
    }

    public function updatedSelectedMetric()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.team-metrics-dashboard');
    }
}