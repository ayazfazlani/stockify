<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'quota_name',
        'used',
        'limit',
        'reset_at',
    ];

    protected $casts = [
        'reset_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getUsagePercentageAttribute()
    {
        return $this->limit > 0 ? ($this->used / $this->limit) * 100 : 0;
    }

    public function getRemainingAttribute()
    {
        return max(0, $this->limit - $this->used);
    }
}