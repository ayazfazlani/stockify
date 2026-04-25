<?php

namespace App\Models;

use App\Traits\HasPlanFeatures;
use Laravel\Cashier\Billable;
// use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use Billable, HasPlanFeatures;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'status',
        'data',
        'subdomain',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'subscription_plan',
        'is_active',
        'plan_id',
        'avatar',
    ];

    public function getTenantKeyName(): string
    {
        return 'slug';
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'owner_id',
            'status',
            'created_at',
            'updated_at',
            'stripe_id',
            'pm_type',
            'pm_last_four',
            'trial_ends_at',
            'subscription_plan',
            'is_active',
            'plan_id',
            'avatar',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'tenant_id', 'id');
    }
}
