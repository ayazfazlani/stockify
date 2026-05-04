<?php

namespace App\Livewire\Marketplace;

use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use App\Services\GeoLocation;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    public ?string $detectedCountry = null;

    public function mount(): void
    {
        $this->detectedCountry = app(GeoLocation::class)->country();
    }

    #[Layout('components.layouts.marketplace')]
    public function render()
    {
        $country = $this->detectedCountry;

        // Prioritize same-country stores, then show others
        $topStores = Store::where('is_public', true)
            ->when($country, function ($q) use ($country) {
                $q->orderByRaw('CASE WHEN country = ? THEN 0 ELSE 1 END', [$country]);
            })
            ->take(6)
            ->get();

        // Prioritize same-country products
        $featuredItems = Item::public()
            ->with(['store', 'category'])
            ->when($country, function ($q) use ($country) {
                $q->whereHas('store', function ($sq) use ($country) {
                    $sq->orderByRaw('CASE WHEN country = ? THEN 0 ELSE 1 END', [$country]);
                });
            })
            ->latest()
            ->take(8)
            ->get();

        // Show same-country cities first
        $cities = Store::where('is_public', true)
            ->whereNotNull('city')
            ->when($country, function ($q) use ($country) {
                $q->where('country', $country);
            })
            ->distinct()
            ->pluck('city')
            ->take(12);

        return view('livewire.marketplace.home', [
            'categories' => Category::where('is_active', true)->withCount('items')->get(),
            'featuredItems' => $featuredItems,
            'topStores' => $topStores,
            'cities' => $cities,
        ]);
    }
}
