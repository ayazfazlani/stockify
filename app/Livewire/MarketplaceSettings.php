<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MarketplaceSettings extends Component
{
    use WithPagination;

    public $store;

    public $is_public;

    public $address;

    public $city;

    public $country;

    public $latitude;

    public $longitude;

    public function mount()
    {
        $this->store = Store::where('id', Auth::user()->store_id)->firstOrFail();
        $this->is_public = (bool) $this->store->is_public;
        $this->address = $this->store->address;
        $this->city = $this->store->city;
        $this->country = $this->store->country;
        $this->latitude = $this->store->latitude;
        $this->longitude = $this->store->longitude;
    }

    public function updateStoreSettings()
    {
        $this->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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
        $item = Item::withoutGlobalScopes()->where('store_id', $this->store->id)->findOrFail($itemId);
        $item->update(['is_public' => ! $item->is_public]);
    }

    public function render()
    {
        $items = Item::withoutGlobalScopes()
            ->where('store_id', $this->store->id)
            ->paginate(10);

        return view('livewire.marketplace-settings', [
            'items' => $items,
        ]);
    }
}
