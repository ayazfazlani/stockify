<?php

namespace App\Models;

use App\Enums\PlanFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $guarded = [];

    /**
     * Subscriptions linked to this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Features assigned to this plan (via the plan_features pivot table).
     */
    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeatureModel::class);
    }

    /**
     * Check if this plan has a specific feature enabled.
     *
     *   $plan->hasFeature(PlanFeature::ANALYTICS)
     *   $plan->hasFeature('analytics')
     */
    public function hasFeature(PlanFeature|string $feature): bool
    {
        $key = $feature instanceof PlanFeature ? $feature->value : $feature;

        // Use the loaded relationship if available (avoids N+1)
        if ($this->relationLoaded('planFeatures')) {
            return $this->planFeatures->contains('feature', $key);
        }

        return $this->planFeatures()->where('feature', $key)->exists();
    }

    /**
     * Get the value/limit for a quota feature.
     *
     *   $plan->getFeatureValue(PlanFeature::MAX_ITEMS) // "500"
     */
    public function getFeatureValue(PlanFeature|string $feature): ?string
    {
        $key = $feature instanceof PlanFeature ? $feature->value : $feature;

        if ($this->relationLoaded('planFeatures')) {
            $pf = $this->planFeatures->firstWhere('feature', $key);

            return $pf ? $pf->value : null;
        }

        return $this->planFeatures()->where('feature', $key)->value('value');
    }

    /**
     * Sync features from admin UI.
     *
     * Expected format:
     *   ['analytics' => null, 'max-items' => '500', 'multi-store' => null]
     *   - null value = boolean feature (enabled)
     *   - string value = quota feature limit
     */
    public function syncFeatures(array $features): void
    {
        // Delete all existing features for this plan
        $this->planFeatures()->delete();

        // Insert new ones
        $records = [];
        foreach ($features as $featureKey => $value) {
            $records[] = [
                'plan_id' => $this->id,
                'feature' => $featureKey,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($records)) {
            PlanFeatureModel::insert($records);
        }

        // Reload the relationship
        $this->load('planFeatures');
    }

    /**
     * Get all feature keys for this plan.
     */
    public function getFeatureKeys(): array
    {
        return $this->planFeatures->pluck('feature')->toArray();
    }

    /**
     * Get features grouped by their PlanFeature enum group.
     */
    public function getFeaturesGrouped(): array
    {
        $grouped = [];
        foreach ($this->planFeatures as $pf) {
            $enum = PlanFeature::tryFrom($pf->feature);
            if ($enum) {
                $group = $enum->group();
                $grouped[$group][] = [
                    'feature' => $enum,
                    'value' => $pf->value,
                ];
            }
        }

        return $grouped;
    }
}
