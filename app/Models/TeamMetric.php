<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'metric_name',
        'value',
        'metadata',
        'recorded_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}