<?php

namespace App\Models;

use App\Traits\TeamScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, TeamScope;

    protected $fillable = [
        'store_id',
        'user_id',
        'total_amount',
        'tenant_id'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
