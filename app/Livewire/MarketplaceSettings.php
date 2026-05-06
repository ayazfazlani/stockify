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

    public $area;

    public $country;

    public $latitude;

    public $longitude;

    public $currency;

    public $currency_symbol;

    public $tenantSlug;

    public $showDeleteModal = false;

    public $confirmName = '';

    public function mount()
    {
        $tenant = tenant();
        $this->tenantSlug = $tenant ? $tenant->slug : Auth::user()->tenant_id;
        $storeId = Auth::user()->store_id;

        $store = null;
        if ($storeId) {
            $store = Store::where('id', $storeId)->first();
        }

        if ($tenant) {
            $this->hasMarketplaceFeature = \DB::table('plan_features')
                ->where('plan_id', $tenant->plan_id)
                ->where('feature', 'marketplace')
                ->exists();
        }

        if (!$store) {
            $fallbackStore = Store::where('tenant_id', Auth::user()->tenant_id)->first();

            if ($fallbackStore) {
                Auth::user()->update(['store_id' => $fallbackStore->id]);
                $store = $fallbackStore;
            } else {
                session()->flash('error', 'You must create a store first before accessing Marketplace Settings.');
                return redirect()->route('tenant.admin', ['tenant' => $this->tenantSlug]);
            }
        }

        $this->store = $store;
        $this->is_public = (bool) $this->store->is_public;
        $this->address = $this->store->address;
        $this->city = $this->store->city;
        $this->area = $this->store->area;
        $this->country = $this->store->country;
        $this->latitude = $this->store->latitude;
        $this->longitude = $this->store->longitude;
        $this->currency = $this->store->currency ?? 'PKR';
        $this->currency_symbol = $this->store->currency_symbol ?? 'Rs.';
    }

    public function getCountriesProperty()
    {
        return [
            'United States',
            'Canada',
            'United Kingdom',
            'Australia',
            'Germany',
            'France',
            'India',
            'Japan',
            'China',
            'Brazil',
            'South Africa',
            'United Arab Emirates',
            'Saudi Arabia',
            'Qatar',
            'Oman',
            'Kuwait',
            'Bahrain',
            'Egypt',
            'Jordan',
            'Lebanon',
            'Turkey',
            'Pakistan',
            'Bangladesh',
            'Sri Lanka',
            'Nepal',
            'Singapore',
            'Malaysia',
            'Indonesia',
            'Thailand',
            'Vietnam',
            'Philippines',
            'South Korea',
            'Italy',
            'Spain',
            'Netherlands',
            'Sweden',
            'Norway',
            'Denmark',
            'Finland',
            'Poland',
            'Russia',
            'Mexico',
            'Argentina',
            'Chile',
            'Colombia',
            'Peru',
            'New Zealand',
            'Nigeria',
            'Kenya',
            'Ethiopia',
            'Ghana',
            'Morocco',
        ];
    }

    public function getCurrenciesProperty()
    {
        return [
            'PKR',
            'USD',
            'EUR',
            'GBP',
            'CAD',
            'AUD',
            'INR',
            'AED',
            'SAR',
            'JPY',
            'CNY',
            'RUB',
            'BRL',
            'ZAR',
            'SGD',
        ];
    }

    public function getSymbolsProperty()
    {
        return [
            'Rs.',
            '$',
            '€',
            '£',
            '₹',
            'DH',
            'SR',
            '¥',
            '₽',
            'R$',
        ];
    }

    public function updatedCountry($value)
    {
        $map = [
            'Pakistan' => ['PKR', 'Rs.'],
            'United States' => ['USD', '$'],
            'United Kingdom' => ['GBP', '£'],
            'Canada' => ['CAD', '$'],
            'Australia' => ['AUD', '$'],
            'India' => ['INR', '₹'],
            'United Arab Emirates' => ['AED', 'DH'],
            'Saudi Arabia' => ['SAR', 'SR'],
            'Germany' => ['EUR', '€'],
            'France' => ['EUR', '€'],
            'Italy' => ['EUR', '€'],
            'Spain' => ['EUR', '€'],
        ];

        if (isset($map[$value])) {
            $this->currency = $map[$value][0];
            $this->currency_symbol = $map[$value][1];
        }
    }

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        session()->flash('status', 'Location coordinates updated successfully.');
    }

    public function updateStoreSettings()
    {
        $tenant = $this->resolveTenant();

        $hasMarketplaceFeature = $tenant && $tenant->hasFeature(PlanFeature::MARKETPLACE);

        if ($this->is_public && !$hasMarketplaceFeature) {
            $this->is_public = false;
            session()->flash('error', 'Your current plan does not support marketplace visibility. Please upgrade to make your store public.');
            return;
        }

        $this->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $this->store->update([
            'is_public' => $this->is_public,
            'address' => $this->address,
            'city' => $this->city,
            'area' => $this->area,
            'country' => $this->country,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        session()->flash('status', 'Marketplace settings updated successfully.');
    }

    public function toggleItemPublic($itemId)
    {
        $tenant = $this->resolveTenant();
        $hasMarketplaceFeature = $tenant && $tenant->hasFeature(PlanFeature::MARKETPLACE);

        if (!$hasMarketplaceFeature) {
            session()->flash('error', 'Your current plan does not support marketplace visibility.');
            return;
        }

        $item = Item::withoutGlobalScopes()->where('store_id', $this->store->id)->findOrFail($itemId);
        $item->update(['is_public' => !$item->is_public]);
        session()->flash('status', 'Item visibility updated successfully.');
    }

    public function confirmDelete()
    {
        $this->confirmName = '';
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->confirmName !== $this->store->name) {
            $this->addError('confirmName', 'The name you typed does not match your store name.');
            return;
        }

        $this->store->delete();
        session()->flash('status', 'Store deleted successfully.');

        return redirect()->route('tenant.dashboard', ['tenant' => $this->tenantSlug]);
    }

    protected function resolveTenant(): ?Tenant
    {
        $resolved = tenant();
        if ($resolved) {
            return $resolved;
        }

        $tenantId = Auth::user()?->tenant_id;
        if (!$tenantId) {
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
            'currencies' => $this->currencies,
            'symbols' => $this->symbols,
        ]);
    }
}