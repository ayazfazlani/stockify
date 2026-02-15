<?php

namespace App\Livewire;

use App\Services\TeamMetricsService;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TeamMetricsDashboard extends Component
{
    public $timeframe = '30';
    public $selectedMetric = 'transactions';
    public $metrics = [];
    public $quotas = [];
    public $team;

    protected $metricsService;

    public function boot(TeamMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        $this->loadData();
    }

    public function loadData()
    {
        // Get metrics for the selected timeframe
        $from = now()->subDays((int)$this->timeframe);
        $metrics = $this->metricsService->getTeamMetrics(
            $this->team,
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
        $this->quotas = $this->metricsService->getAllQuotas($this->team)->map(function($quota) {
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