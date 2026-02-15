<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\Auth;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('team', function ($query) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $query->where(function ($q) {
                    $q->whereNull('team_id')
                      ->orWhere('team_id', Auth::user()->current_team_id);
                });
            }
        });
    }
}
