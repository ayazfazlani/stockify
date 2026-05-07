<?php

namespace App\Livewire;

use App\Enums\PlanFeature;
use App\Models\Item;
use App\Models\Store;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MarketplaceSettings extends Component
{
    use WithFileUploads;

    public $store;

    public $hasMarketplaceFeature = false;

    public $name;

    public $slug;

    public $description;

    public $is_public;

    public $address;

    public $city;

    public $area;

    public $country;

    public $latitude;

    public $longitude;

    public $currency;

    public $currency_symbol;

    public $logo;

    public $banner;

    public $tenantSlug;

    public $showDeleteModal = false;

    public $confirmName = '';

    // Edit Item
    public $showEditItemModal = false;
    public $editingItem = null;
    public $editItemName = '';
    public $editItemDescription = '';
    public $editItemPrice = 0;
    public $editItemImage;

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
        $this->name = $this->store->name;
        $this->slug = $this->store->slug;
        $this->description = $this->store->description;
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|max:100|unique:stores,slug,' . $this->store->id,
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|max:1024',
            'banner' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'address' => $this->address,
            'city' => $this->city,
            'area' => $this->area,
            'country' => $this->country,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        if ($this->logo) {
            if ($this->store->logo) {
                Storage::disk('public')->delete($this->store->logo);
            }
            $data['logo'] = $this->logo->store('logos', 'public');
        }

        if ($this->banner) {
            if ($this->store->banner) {
                Storage::disk('public')->delete($this->store->banner);
            }
            $data['banner'] = $this->banner->store('banners', 'public');
        }

        $this->store->update($data);

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

    public function editItem($itemId)
    {
        $this->editingItem = Item::withoutGlobalScopes()->where('store_id', $this->store->id)->findOrFail($itemId);
        $this->editItemName = $this->editingItem->name;
        $this->editItemDescription = $this->editingItem->description;
        $this->editItemPrice = $this->editingItem->price;
        $this->editItemImage = null; // Clear any previously uploaded file
        $this->showEditItemModal = true;
    }

    public function updateItem()
    {
        if (!$this->editingItem) return;

        $this->validate([
            'editItemName' => 'required|string|max:255',
            'editItemDescription' => 'nullable|string',
            'editItemPrice' => 'required|numeric|min:0',
            'editItemImage' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->editItemName,
            'description' => $this->editItemDescription,
            'price' => $this->editItemPrice,
        ];

        if ($this->editItemImage) {
            if ($this->editingItem->image) {
                Storage::disk('public')->delete($this->editingItem->image);
            }
            $data['image'] = $this->editItemImage->store('items', 'public');
        }

        $this->editingItem->update($data);

        session()->flash('status', 'Item marketplace details updated successfully!');
        $this->showEditItemModal = false;
        $this->editingItem = null;
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