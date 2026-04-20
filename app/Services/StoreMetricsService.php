<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreMetric;
use App\Models\StoreQuota;
use Carbon\Carbon;

class StoreMetricsService
{
    public function recordMetric(Store $store, string $metricName, float $value, array $metadata = [])
    {
        return StoreMetric::create([
            'store_id' => $store->id,
            'metric_name' => $metricName,
            'value' => $value,
            'metadata' => $metadata,
            'recorded_at' => now(),
        ]);
    }

    public function updateQuota(Store $store, string $quotaName, float $used)
    {
        return StoreQuota::updateOrCreate(
            ['store_id' => $store->id, 'quota_name' => $quotaName],
            [
                'used' => $used,
                'limit' => $this->getQuotaLimit($store, $quotaName),
                'reset_at' => $this->getNextResetDate($quotaName),
            ]
        );
    }

    public function incrementQuota(Store $store, string $quotaName, float $increment)
    {
        $quota = StoreQuota::firstOrCreate(
            ['store_id' => $store->id, 'quota_name' => $quotaName],
            [
                'used' => 0,
                'limit' => $this->getQuotaLimit($store, $quotaName),
                'reset_at' => $this->getNextResetDate($quotaName),
            ]
        );

        $quota->used += $increment;
        $quota->save();

        return $quota;
    }

    public function checkQuota(Store $store, string $quotaName, float $additional = 0): bool
    {
        $quota = StoreQuota::where('store_id', $store->id)
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

    protected function getQuotaLimit(Store $store, string $quotaName): float
    {
        $plan = config("saas.plans.{$store->subscription_plan}");

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

    public function getStoreMetrics(Store $store, string $metricName, Carbon $from, Carbon $to)
    {
        return StoreMetric::where('store_id', $store->id)
            ->where('metric_name', $metricName)
            ->whereBetween('recorded_at', [$from, $to])
            ->orderBy('recorded_at')
            ->get();
    }

    public function getDailyMetrics(Store $store, string $metricName, int $days = 30)
    {
        return StoreMetric::where('store_id', $store->id)
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

    public function getAllQuotas(Store $store)
    {
        return StoreQuota::where('store_id', $store->id)->get();
    }
}