<?php

namespace App\Livewire\Marketplace;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class StoreProfile extends Component
{
    use WithPagination;

    public Store $store;

    public $search = '';

    public function mount(Store $store)
    {
        $this->store = $store;
        
        if (!$this->store->is_public) {
            abort(404);
        }
    }

    public function render()
    {
        $items = Item::where('store_id', $this->store->id)
            ->where('is_public', true)
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(12);

        return view('livewire.marketplace.store-profile', [
            'items' => $items,
        ])->layout('components.layouts.marketplace');
    }
}
