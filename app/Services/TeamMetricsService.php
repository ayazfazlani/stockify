<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMetric;
use App\Models\TeamQuota;
use Carbon\Carbon;

class TeamMetricsService
{
    public function recordMetric(Team $team, string $metricName, float $value, array $metadata = [])
    {
        return TeamMetric::create([
            'team_id' => $team->id,
            'metric_name' => $metricName,
            'value' => $value,
            'metadata' => $metadata,
            'recorded_at' => now(),
        ]);
    }

    public function updateQuota(Team $team, string $quotaName, float $used)
    {
        return TeamQuota::updateOrCreate(
            ['team_id' => $team->id, 'quota_name' => $quotaName],
            [
                'used' => $used,
                'limit' => $this->getQuotaLimit($team, $quotaName),
                'reset_at' => $this->getNextResetDate($quotaName),
            ]
        );
    }

    public function incrementQuota(Team $team, string $quotaName, float $increment)
    {
        $quota = TeamQuota::firstOrCreate(
            ['team_id' => $team->id, 'quota_name' => $quotaName],
            [
                'used' => 0,
                'limit' => $this->getQuotaLimit($team, $quotaName),
                'reset_at' => $this->getNextResetDate($quotaName),
            ]
        );

        $quota->used += $increment;
        $quota->save();

        return $quota;
    }

    public function checkQuota(Team $team, string $quotaName, float $additional = 0): bool
    {
        $quota = TeamQuota::where('team_id', $team->id)
            ->where('quota_name', $quotaName)
            ->first();

        if (!$quota) {
            return true;
        }

        // Reset quota if needed
        if ($quota->reset_at && $quota->reset_at->isPast()) {
            $quota->used = 0;
            $quota->reset_at = $this->getNextResetDate($quotaName);
            $quota->save();
        }

        return ($quota->used + $additional) <= $quota->limit;
    }

    protected function getQuotaLimit(Team $team, string $quotaName): float
    {
        $plan = config("saas.plans.{$team->subscription_plan}");

        switch ($quotaName) {
            case 'storage':
                return $plan['features']['storage'] ?? 0;
            case 'api_requests':
                return $plan['features']['api_requests'] ?? 0;
            case 'export_rows':
                return $plan['features']['export_rows'] ?? 0;
            default:
                return 0;
        }
    }

    protected function getNextResetDate(string $quotaName): ?Carbon
    {
        switch ($quotaName) {
            case 'api_requests':
                return now()->addDay()->startOfDay();
            case 'export_rows':
                return now()->addMonth()->startOfMonth();
            default:
                return null;
        }
    }

    public function getTeamMetrics(Team $team, string $metricName, Carbon $from, Carbon $to)
    {
        return TeamMetric::where('team_id', $team->id)
            ->where('metric_name', $metricName)
            ->whereBetween('recorded_at', [$from, $to])
            ->orderBy('recorded_at')
            ->get();
    }

    public function getDailyMetrics(Team $team, string $metricName, int $days = 30)
    {
        return TeamMetric::where('team_id', $team->id)
            ->where('metric_name', $metricName)
            ->where('recorded_at', '>=', now()->subDays($days))
            ->orderBy('recorded_at')
            ->get()
            ->groupBy(function ($metric) {
                return $metric->recorded_at->format('Y-m-d');
            })
            ->map(function ($metrics) {
                return $metrics->sum('value');
            });
    }

    public function getAllQuotas(Team $team)
    {
        return TeamQuota::where('team_id', $team->id)->get();
    }
}