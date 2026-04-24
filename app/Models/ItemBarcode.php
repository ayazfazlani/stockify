<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBarcode extends Model
{
    protected $fillable = [
        'item_id',
        'tenant_id',
        'store_id',
        'code',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
