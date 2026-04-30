<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Store extends Model
{
    use Billable, HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'owner_id',
        'tenant_id',
        'description',
        'subscription_plan',
        'is_on_trial',
        'is_active',
        'features',
        'member_limit',
        'storage_limit',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'is_public',
    ];

    protected $casts = [
        'features' => 'array',
        'is_on_trial' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    // Relationship with User (Owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship with Users (Team Members)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Validate team membership requirements
    public function validateTeamMembership()
    {
        $adminCount = $this->users()->whereHas('roles', function ($query) {
            $query->where('name', 'team admin');
        })->count();

        $memberCount = $this->users()->count();

        return $adminCount >= 1 && $memberCount >= 2;
    }

    // Method to add a user to the team
    public function addUser(User $user)
    {
        return $this->users()->save($user);
    }

    // Method to remove a user from the team
    public function removeUser(User $user)
    {
        $user->team_id = null;

        return $user->save();
    }

    public function quotas()
    {
        return $this->hasMany(StoreQuota::class, 'store_id');
    }

    public function quota()
    {
        return $this->hasOne(StoreQuota::class, 'store_id')->ofMany('id', 'max');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
