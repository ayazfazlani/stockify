<?php

namespace App\Services;

class FeatureService
{
    public function teamHasFeature($team, $feature)
    {
        if (!$team) {
            return false;
        }

        // Super admin bypass
        if ($team->owner && $team->owner->isSuperAdmin()) {
            return true;
        }

        // Check if team has active subscription
        if (!$team->hasActiveSubscription()) {
            return false;
        }

        // Check if feature exists in team's features
        return $team->hasFeature($feature);
    }

    public function teamCanAddMember($team)
    {
        if (!$team) {
            return false;
        }

        // Super admin bypass
        if ($team->owner && $team->owner->isSuperAdmin()) {
            return true;
        }

        // Check if team has active subscription
        if (!$team->hasActiveSubscription()) {
            return false;
        }

        // Check member limit
        return !$team->hasReachedMemberLimit();
    }

    public function teamHasStorageSpace($team, $size)
    {
        if (!$team) {
            return false;
        }

        // Super admin bypass
        if ($team->owner && $team->owner->isSuperAdmin()) {
            return true;
        }

        // Check if team has active subscription
        if (!$team->hasActiveSubscription()) {
            return false;
        }

        // Check if adding the size would exceed the limit
        $currentUsage = $team->getStorageUsage();
        return ($currentUsage + $size) <= $team->storage_limit;
    }
}