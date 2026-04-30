<?php

namespace App\Livewire\Marketplace;

use App\Models\Category;
use App\Models\Item;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'cat')]
    public $category = '';

    #[Url(as: 'sort')]
    public $sort = 'latest';

    #[Url(as: 'price')]
    public $priceRange = '';

    #[Url(as: 'dist')]
    public $maxDistance = 50; // Default 50km

    public $lat;

    public $lng;

    public $userLocationName = 'Nearby';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'latest'],
        'priceRange' => ['except' => ''],
        'maxDistance' => ['except' => 50],
    ];

    public function mount(?Category $category = null)
    {
        if ($category) {
            $this->category = $category->slug;
        }
    }

    public function setLocation($lat, $lng, $name = null)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        if ($name) {
            $this->userLocationName = $name;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = Item::public()
            ->with(['store', 'category'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%')
                        ->orWhere('brand', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->category, function ($q) {
                $q->whereHas('category', function ($catQ) {
                    $catQ->where('slug', $this->category);
                });
            })
            ->when($this->priceRange, function ($q) {
                [$min, $max] = explode('-', $this->priceRange);
                $q->whereBetween('price', [(float) $min, (float) $max]);
            });

        // Location filtering (Haversine)
        if ($this->lat && $this->lng) {
            $query->join('stores', 'items.store_id', '=', 'stores.id')
                ->select('items.*')
                ->selectRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( stores.latitude ) ) * cos( radians( stores.longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( stores.latitude ) ) ) ) AS distance', [$this->lat, $this->lng, $this->lat])
                ->having('distance', '<=', $this->maxDistance);
        }

        // Sorting
        if ($this->sort === 'distance' && $this->lat && $this->lng) {
            $query->orderBy('distance', 'asc');
        } else {
            switch ($this->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest('items.created_at');
                    break;
            }
        }

        return view('livewire.marketplace.search', [
            'items' => $query->paginate(12),
            'categories' => Category::where('is_active', true)->get(),
        ])->layout('components.layouts.marketplace');
    }
}
