<?php

namespace App\Traits;

use App\Enums\PlanFeature;
use App\Models\Plan;

trait HasPlanFeatures
{
    /**
     * Cached plan instance for the current request.
     */
    protected ?Plan $cachedPlan = null;

    protected bool $planCacheLoaded = false;

    /**
     * Check if tenant's current plan has a feature.
     *
     * Usage:
     *   $tenant->hasFeature(PlanFeature::ANALYTICS)
     *   $tenant->hasFeature('analytics')
     */
    public function hasFeature(PlanFeature|string $feature): bool
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return false;
        }

        return $plan->hasFeature($feature);
    }

    /**
     * Get the quota/limit value for a feature.
     *
     * Usage:
     *   $tenant->getFeatureLimit(PlanFeature::MAX_ITEMS) // returns 500
     *   $tenant->getFeatureLimit('max-items')            // returns 500
     */
    public function getFeatureLimit(PlanFeature|string $feature): ?int
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return null;
        }

        $value = $plan->getFeatureValue($feature);

        if ($value === null) {
            return null;
        }

        return (int) $value;
    }

    /**
     * Check if a quota feature has been exceeded.
     *
     * Usage:
     *   $tenant->hasExceededLimit(PlanFeature::MAX_ITEMS, Item::count())
     */
    public function hasExceededLimit(PlanFeature|string $feature, int $currentUsage): bool
    {
        $limit = $this->getFeatureLimit($feature);

        // No limit set or feature not found = not allowed
        if ($limit === null) {
            return true;
        }

        // -1 means unlimited
        if ($limit === -1) {
            return false;
        }

        return $currentUsage >= $limit;
    }

    /**
     * Check if tenant can still add more of something (inverse of hasExceededLimit).
     *
     * Usage:
     *   $tenant->canAdd(PlanFeature::MAX_ITEMS, Item::count())
     *   $tenant->canAdd(PlanFeature::MAX_STORES, $this->stores()->count())
     */
    public function canAdd(PlanFeature|string $feature, int $currentCount): bool
    {
        return ! $this->hasExceededLimit($feature, $currentCount);
    }

    /**
     * Get remaining quota for a feature.
     *
     * Returns null if feature not found, -1 if unlimited.
     *
     * Usage:
     *   $tenant->getRemainingQuota(PlanFeature::MAX_ITEMS, Item::count())
     */
    public function getRemainingQuota(PlanFeature|string $feature, int $currentUsage): ?int
    {
        $limit = $this->getFeatureLimit($feature);

        if ($limit === null) {
            return null;
        }

        if ($limit === -1) {
            return -1; // unlimited
        }

        return max(0, $limit - $currentUsage);
    }

    /**
     * Get all features for the current plan.
     *
     * Returns an array of feature keys.
     */
    public function getAllFeatures(): array
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return [];
        }

        return $plan->planFeatures->pluck('feature')->toArray();
    }

    /**
     * Get the current plan with features eagerly loaded.
     */
    public function currentPlan(): ?Plan
    {
        if (! $this->planCacheLoaded) {
            $this->cachedPlan = $this->plan_id
                ? Plan::with('planFeatures')->find($this->plan_id)
                : null;
            $this->planCacheLoaded = true;
        }

        return $this->cachedPlan;
    }

    /**
     * Clear the cached plan (useful after plan change).
     */
    public function clearPlanCache(): void
    {
        $this->cachedPlan = null;
        $this->planCacheLoaded = false;
    }
}
