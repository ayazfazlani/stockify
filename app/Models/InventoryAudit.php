<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    protected $fillable = [
        'tenant_id',
        'store_id',
        'item_id',
        'user_id',
        'action',
        'before_qty',
        'change_qty',
        'after_qty',
        'reason',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
