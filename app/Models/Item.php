<?php

namespace App\Models;

use App\Traits\TeamScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory, TeamScope;

    const TRACKING_STANDARD = 'standard';

    const TRACKING_SERIALIZED = 'serialized';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'store_id',
        'user_id',
        'sku',
        'name',
        'image',
        'cost',
        'price',
        'type',
        'brand',
        'quantity',
        'tracking_type',
        'reorder_level',
        'reorder_quantity',
        'supplier_id',
        'description',
        'is_public',
        'category_id',
        'slug',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function serials()
    {
        return $this->hasMany(ItemSerial::class);
    }

    public function barcodes()
    {
        return $this->hasMany(ItemBarcode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function analytics()
    {
        return $this->hasOne(Analytics::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublic($query)
    {
        return $query->where($this->getTable().'.is_public', true)
            ->whereNotNull($this->getTable().'.slug')
            ->where($this->getTable().'.slug', '<>', '')
            ->whereHas('store', function ($q) {
                $q->where('is_public', true);
            });
    }

    protected static function booted()
    {
        static::saving(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name).'-'.Str::random(4);
            }
        });

        static::creating(function ($item) {
            if (empty($item->sku)) {
                $item->sku = 'SKU-'.strtoupper(Str::random(8));
            }
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name).'-'.Str::random(4);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function resolveByCode(string $code, ?int $storeId = null): ?self
    {
        $normalizedCode = trim($code);
        if ($normalizedCode === '') {
            return null;
        }

        return static::query()
            ->when($storeId, fn ($query) => $query->where('store_id', $storeId))
            ->where(function ($query) use ($normalizedCode) {
                $query->where('sku', $normalizedCode)
                    ->orWhereHas('barcodes', function ($barcodeQuery) use ($normalizedCode) {
                        $barcodeQuery->where('code', $normalizedCode);
                    });
            })
            ->first();
    }
}
