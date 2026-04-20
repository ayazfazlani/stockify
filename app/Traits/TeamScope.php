<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait TeamScope
{
  protected static function bootTeamScope()
  {
    static::addGlobalScope('team', function (Builder $builder) {
      $user = Auth::user();

      if (!$user) {
        return;
      }

      // Super admin can see all data
      if ($user->hasRole('super admin')) {
        return;
      }

      $table = $builder->getModel()->getTable();
      $hasStoreId = Schema::hasColumn($table, 'store_id');
      $hasTeamId = Schema::hasColumn($table, 'team_id');
      $hasUserIdColumn = Schema::hasColumn($table, 'user_id');

      // Primary scoping column: store_id, secondary: team_id
      $scopeColumn = $hasStoreId ? 'store_id' : ($hasTeamId ? 'team_id' : null);

      if (!$scopeColumn && !$hasUserIdColumn) {
        return;
      }

      // Get the current store ID from the user context
      $currentStoreId = $user->current_team_id; // Still stored in current_team_id column in users table

      if ($currentStoreId) {
        $builder->where(function ($query) use ($user, $currentStoreId, $scopeColumn, $hasUserIdColumn) {
          if ($scopeColumn) {
            $query->where($scopeColumn, $currentStoreId);
          }
          if ($hasUserIdColumn) {
            if ($scopeColumn) {
               $query->orWhere('user_id', $user->id);
            } else {
               $query->where('user_id', $user->id);
            }
          }
        });
        return;
      }

      // Fallback if no store is selected
      if ($hasUserIdColumn) {
        $builder->where('user_id', $user->id);
      } elseif ($scopeColumn) {
        $builder->whereNull($scopeColumn);
      }
    });

    // Auto-set scoping IDs on model creation
    static::creating(function ($model) {
      $table = $model->getTable();
      $hasStoreId = Schema::hasColumn($table, 'store_id');
      $hasTeamId = Schema::hasColumn($table, 'team_id');
      $currentStoreId = Auth::check() ? Auth::user()->current_team_id : null;

      if ($currentStoreId) {
        if ($hasStoreId && !isset($model->store_id)) {
          $model->store_id = $currentStoreId;
        }
        if ($hasTeamId && !isset($model->team_id)) {
          $model->team_id = $currentStoreId;
        }
      }

      if (Schema::hasColumn($table, 'user_id') && !isset($model->user_id) && Auth::check()) {
        $model->user_id = Auth::id();
      }
    });
  }

  // Helper method to check if a model belongs to a team/store
  public function belongsToTeam($storeId)
  {
    if (isset($this->store_id)) {
      return $this->store_id === $storeId;
    }
    if (isset($this->team_id)) {
      return $this->team_id === $storeId;
    }
    return false;
  }
}
