<?php

namespace App\Livewire\Marketplace;

use App\Models\Item;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProductDetails extends Component
{
    public Item $item;

    public function mount(Item $item)
    {
        $this->item = $item;

        // Ensure it's public
        if (! $item->is_public || ! $item->store->is_public) {
            abort(404);
        }

        abort_if(! $this->item, 404);
    }

    #[Layout('components.layouts.marketplace')]
    public function render()
    {
        $relatedItems = Item::public()
            ->where('category_id', $this->item->category_id)
            ->where('id', '!=', $this->item->id)
            ->take(4)
            ->get();

        return view('livewire.marketplace.product-details', [
            'relatedItems' => $relatedItems,
        ]);
    }
}
