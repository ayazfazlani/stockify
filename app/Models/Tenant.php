<?php

namespace App\Models;

use Laravel\Cashier\Billable;
// use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use Billable;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'status',
        'data',
        'subdomain',
    ];

    public function getTenantKeyName(): string
    {
        return 'slug';
    }

    /**
     * Direct database columns (not stored in JSON data)
     */
    protected $directColumns = [
        'id',
        'name',
        'slug',
        'owner_id',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Override setAttribute to handle direct columns properly
     */
    public function setAttribute($key, $value)
    {
        // If it's a direct column, use the parent's normal attribute setting
        if (in_array($key, $this->directColumns)) {
            parent::setAttribute($key, $value);
        } else {
            // Otherwise, let VirtualColumn handle it (stores in data column)
            parent::setAttribute($key, $value);
        }
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'tenant_id', 'id');
    }
}
