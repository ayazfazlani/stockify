<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'tenant_id',
        'store_id',
        'supplier_id',
        'created_by',
        'status',
        'expected_date',
        'ordered_at',
        'received_at',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'expected_date' => 'date',
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
