<?php

namespace App\Services;

use App\Enums\PlanFeature;
use App\Models\Tenant;

class FeatureService
{
    /**
     * Check if a tenant has a specific feature.
     */
    public function tenantHasFeature(?Tenant $tenant, PlanFeature|string $feature): bool
    {
        if (! $tenant) {
            return false;
        }

        return $tenant->hasFeature($feature);
    }

    /**
     * Check if a tenant can add more of a quota-limited resource.
     */
    public function tenantCanAdd(?Tenant $tenant, PlanFeature|string $feature, int $currentCount): bool
    {
        if (! $tenant) {
            return false;
        }

        return $tenant->canAdd($feature, $currentCount);
    }

    /**
     * Get the feature limit for a tenant's plan.
     */
    public function getLimit(?Tenant $tenant, PlanFeature|string $feature): ?int
    {
        if (! $tenant) {
            return null;
        }

        return $tenant->getFeatureLimit($feature);
    }

    /**
     * Get remaining quota for a tenant.
     */
    public function getRemaining(?Tenant $tenant, PlanFeature|string $feature, int $currentUsage): ?int
    {
        if (! $tenant) {
            return null;
        }

        return $tenant->getRemainingQuota($feature, $currentUsage);
    }

    /**
     * Check if a tenant can add a new store.
     */
    public function canAddStore(?Tenant $tenant): bool
    {
        if (! $tenant) {
            return false;
        }

        $currentStores = $tenant->stores()->count();

        return $tenant->canAdd(PlanFeature::MAX_STORES, $currentStores);
    }

    /**
     * Check if a store can add a new team member.
     */
    public function canAddTeamMember(?Tenant $tenant, int $currentMemberCount): bool
    {
        if (! $tenant) {
            return false;
        }

        return $tenant->canAdd(PlanFeature::MAX_TEAM_MEMBERS, $currentMemberCount);
    }

    /**
     * Check if a tenant can add more items.
     */
    public function canAddItem(?Tenant $tenant, int $currentItemCount): bool
    {
        if (! $tenant) {
            return false;
        }

        return $tenant->canAdd(PlanFeature::MAX_ITEMS, $currentItemCount);
    }
}