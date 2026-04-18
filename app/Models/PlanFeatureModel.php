<?php

namespace App\Models;

use App\Enums\PlanFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeatureModel extends Model
{
    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature',
        'value',
    ];

    /**
     * Get the plan that this feature belongs to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the PlanFeature enum instance for this record.
     */
    public function getFeatureEnumAttribute(): ?PlanFeature
    {
        return PlanFeature::tryFrom($this->feature);
    }

    /**
     * Check if this feature is a boolean type.
     */
    public function isBooleanFeature(): bool
    {
        $enum = $this->feature_enum;

        return $enum && $enum->type() === 'boolean';
    }

    /**
     * Check if this feature is a quota type.
     */
    public function isQuotaFeature(): bool
    {
        $enum = $this->feature_enum;

        return $enum && $enum->type() === 'quota';
    }

    /**
     * Get the numeric value for quota features.
     */
    public function getNumericValueAttribute(): ?int
    {
        if ($this->value === null) {
            return null;
        }

        return (int) $this->value;
    }

    /**
     * Check if this quota is unlimited (-1).
     */
    public function isUnlimited(): bool
    {
        return $this->numeric_value === -1;
    }
}
