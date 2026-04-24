<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'tenant_id',
        'store_id',
        'name',
        'contact_name',
        'email',
        'phone',
        'whatsapp',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
