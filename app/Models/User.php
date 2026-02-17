<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $guarded = [];

    // Relationship with Team
    public function teams()
    {
        return $this->belongsToMany(Store::class);
    }

    // Check if user is a Super Admin
    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

    // Check if user is a Team Admin
    public function isStoreAdmin()
    {
        return $this->hasRole('team admin');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
        'is_super_admin',
        'google_id',
        'avatar',
        'tenant_id',
        'store_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add relationship for current team
    public function currentTeam()
    {
        return $this->belongsTo(Store::class, 'current_team_id');
    }

    // Helper method to switch current team
    public function switchTeam($teamId)
    {
        // Verify user belongs to this team
        if (!$this->teams()->where('store_id', $teamId)->exists()) {
            return false;
        }

        $this->current_team_id = $teamId;
        $this->save();

        // Clear any cached permissions
        $this->forgetCachedPermissions();

        return true;
    }

    public function getCurrentStoreId()
    {
        // Get store ID from session (for multi-store users)
        $storeId = (int) session('current_store_id');

        // Validate store membership via pivot table
        if ($storeId && $this->teams()->where('store_id', $storeId)->exists()) {
            return $storeId;
        }

        // Fallback for single-store users (get first store from pivot)
        return $this->teams()->first()->id ?? null;
    }

    // Get all teams user has access to
    public function accessibleTeams()
    {
        if ($this->hasRole('super admin')) {
            return Store::all();
        }

        return $this->teams;
    }

    // Subscribtion fields
    // Add this to your existing User model
    // public function subscription(): HasOne
    // {
    //     return $this->hasOne(Subscription::class);
    // }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    // Helper methods
    public function hasActiveSubscription(): bool
    {
        $tenant = tenant();
        return $tenant && $tenant->subscribed('default');
    }

    public function canCreateMoreStores(): bool
    {
        $tenant = tenant();
        if (!$tenant || !$tenant->subscribed('default')) {
            return false;
        }

        $currentStoreCount = $this->stores()->count();
        $plan = Plan::where('slug', $tenant->subscription_plan)->first();

        if (!$plan)
            return false;

        $allowedStores = $plan->max_stores;

        return $currentStoreCount < $allowedStores;
    }

    public function isAdmin(): bool
    {
        // Assuming you have a 'role' column or similar
        // Adjust the logic based on your application's role/permission system
        // return $this->role === 'admin' || $this->role === 'super_admin';
        return true;
    }
}
