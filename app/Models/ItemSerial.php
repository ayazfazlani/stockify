<?php

namespace App\Models;

use App\Traits\TeamScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemSerial extends Model
{
    use HasFactory, TeamScope;

    protected $fillable = [
        'item_id',
        'serial_number',
        'status',
        'store_id',
        'tenant_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
