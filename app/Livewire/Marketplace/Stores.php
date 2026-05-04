<?php

namespace App\Livewire\Marketplace;

use App\Models\Store;
use App\Services\GeoLocation;
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

    #[Url(as: 'area')]
    public $area = '';

    #[Url(as: 'country')]
    public $country = '';

    public $lat;

    public $lng;

    public $userLocationName = 'Nearby';

    public ?string $detectedCountry = null;

    public function mount(): void
    {
        $this->detectedCountry = app(GeoLocation::class)->country();

        // Auto-set country filter from geo-detection if not explicitly set
        if (empty($this->country) && $this->detectedCountry) {
            $this->country = $this->detectedCountry;
        }
    }

    public function setLocation($lat, $lng, $name = null): void
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
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->city, function ($q) {
                $q->where('city', $this->city);
            })
            ->when($this->area, function ($q) {
                $q->where('area', $this->area);
            })
            ->when($this->country, function ($q) {
                $q->where('country', $this->country);
            });

        // Get unique filter values
        $cities = Store::where('is_public', true)
            ->when($this->country, fn ($q) => $q->where('country', $this->country))
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city');

        $areas = Store::where('is_public', true)
            ->when($this->country, fn ($q) => $q->where('country', $this->country))
            ->when($this->city, fn ($q) => $q->where('city', $this->city))
            ->whereNotNull('area')
            ->where('area', '<>', '')
            ->distinct()
            ->pluck('area');

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
                'area' => $store->area,
                'slug' => $store->slug,
            ];
        })->filter(fn ($m) => $m['lat'] && $m['lng'])->values();

        return view('livewire.marketplace.stores', [
            'stores' => $stores,
            'cities' => $cities,
            'areas' => $areas,
            'countries' => $countries,
            'markers' => $markers,
        ])->layout('components.layouts.marketplace');
    }
}
