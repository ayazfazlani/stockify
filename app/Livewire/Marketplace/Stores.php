<?php

namespace App\Livewire\Marketplace;

use App\Models\Store;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Stores extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'city')]
    public $city = '';

    #[Url(as: 'country')]
    public $country = '';

    public $lat;

    public $lng;

    public $userLocationName = 'Nearby';

    public function mount()
    {
        // Optional: Get user location from IP or browser if needed later
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
        $query = Store::where('is_public', true)
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->city, function ($q) {
                $q->where('city', $this->city);
            })
            ->when($this->country, function ($q) {
                $q->where('country', $this->country);
            });

        // Get unique cities and countries for filters
        $cities = Store::where('is_public', true)->whereNotNull('city')->distinct()->pluck('city');
        $countries = Store::where('is_public', true)->whereNotNull('country')->distinct()->pluck('country');

        $stores = $query->paginate(12);

        // Prepare map markers
        $markers = $stores->map(function ($store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
                'lat' => $store->latitude,
                'lng' => $store->longitude,
                'city' => $store->city,
                'slug' => $store->slug,
            ];
        })->filter(fn ($m) => $m['lat'] && $m['lng'])->values();

        return view('livewire.marketplace.stores', [
            'stores' => $stores,
            'cities' => $cities,
            'countries' => $countries,
            'markers' => $markers,
        ])->layout('components.layouts.marketplace');
    }
}
