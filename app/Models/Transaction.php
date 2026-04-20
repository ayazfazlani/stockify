<?php

namespace App\Models;

use App\Traits\TeamScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory, TeamScope;

    protected $guarded = [];

    // Assuming this will store the type of transaction: 'added', 'updated', 'deleted'
    protected $casts = [
        'type' => 'string',  // 'added', 'updated', 'deleted'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class);
    }

    public function stockOut()
    {
        return $this->belongsTo(StockOut::class);
    }

    public function team()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Set store_id from item or current user's team
            if (empty($transaction->store_id)) {
                if ($transaction->item) {
                    $transaction->store_id = $transaction->item->store_id;
                } elseif (Auth::check() && Auth::user()->current_team_id) {
                    $transaction->store_id = Auth::user()->current_team_id;
                }
            }

            // Set user_id if the column exists
            if (Schema::hasColumn('transactions', 'user_id') && empty($transaction->user_id)) {
                if ($transaction->item) {
                    $transaction->user_id = $transaction->item->user_id;
                } elseif (Auth::check()) {
                    $transaction->user_id = Auth::id();
                }
            }
        });
    }
}
