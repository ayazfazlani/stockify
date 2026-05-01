<?php

namespace App\Livewire;

use App\Enums\PlanFeature;
use App\Models\Item;
use App\Models\Store;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MarketplaceSettings extends Component
{
    public $store;

    public $is_public;

    public $address;

    public $city;

    public $country;

    public $latitude;

    public $longitude;

    public $tenantSlug;

    public function mount()
    {
        $tenant = tenant();
        $this->tenantSlug = $tenant ? $tenant->slug : Auth::user()->tenant_id;
        $this->store = Store::where('id', Auth::user()->store_id)->firstOrFail();
        $this->is_public = (bool) $this->store->is_public;
        $this->address = $this->store->address;
        $this->city = $this->store->city;
        $this->country = $this->store->country;
        $this->latitude = $this->store->latitude;
        $this->longitude = $this->store->longitude;
    }

    public function getCountriesProperty()
    {
        return [
            'United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'India', 'Japan', 'China', 'Brazil', 'South Africa', 'United Arab Emirates', 'Saudi Arabia', 'Qatar', 'Oman', 'Kuwait', 'Bahrain', 'Egypt', 'Jordan', 'Lebanon', 'Turkey', 'Pakistan', 'Bangladesh', 'Sri Lanka', 'Nepal', 'Singapore', 'Malaysia', 'Indonesia', 'Thailand', 'Vietnam', 'Philippines', 'South Korea', 'Italy', 'Spain', 'Netherlands', 'Sweden', 'Norway', 'Denmark', 'Finland', 'Poland', 'Russia', 'Mexico', 'Argentina', 'Chile', 'Colombia', 'Peru', 'New Zealand', 'Nigeria', 'Kenya', 'Ethiopia', 'Ghana', 'Morocco',
        ];
    }

    public function fetchCoordinates()
    {
        // This will be called via dispatch from Alpine.js or directly if using a geocoding service
        // For now, let's assume we use browser geolocation in the frontend and update these values
    }

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function updateStoreSettings()
    {
        $tenant = $this->resolveTenant();
        $hasMarketplaceFeature = $tenant && $tenant->hasFeature(PlanFeature::MARKETPLACE);

        if ($this->is_public && ! $hasMarketplaceFeature) {
            $this->is_public = false;
            session()->flash('error', 'Your current plan does not support marketplace visibility. Please upgrade to make your store public.');

            return;
        }

        $this->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $this->store->update([
            'is_public' => $this->is_public,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        session()->flash('status', 'Marketplace settings updated successfully.');
    }

    public function toggleItemPublic($itemId)
    {
        $tenant = tenant();
        if (! $tenant || ! $tenant->hasFeature(PlanFeature::MARKETPLACE)) {
            session()->flash('error', 'Your current plan does not support marketplace visibility.');

            return;
        }

        $item = Item::withoutGlobalScopes()->where('store_id', $this->store->id)->findOrFail($itemId);
        $item->update(['is_public' => ! $item->is_public]);
    }

    protected function resolveTenant(): ?Tenant
    {
        $resolved = tenant();
        if ($resolved) {
            return $resolved;
        }

        $tenantId = Auth::user()?->tenant_id;
        if (! $tenantId) {
            return null;
        }

        return Tenant::query()->find($tenantId);
    }

    public function render()
    {
        $items = Item::withoutGlobalScopes()
            ->where('store_id', $this->store->id)
            ->paginate(10);

        return view('livewire.marketplace-settings', [
            'items' => $items,
            'countries' => $this->countries,
        ]);
    }
}
