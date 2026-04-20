<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreQuota extends Model
{
    use HasFactory;

    protected $table = 'store_quotas';

    protected $fillable = [
        'store_id',
        'quota_name',
        'used',
        'limit',
        'reset_at',
    ];

    protected $casts = [
        'reset_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
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