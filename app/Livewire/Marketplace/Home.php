<?php

namespace App\Livewire\Marketplace;

use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('components.layouts.marketplace')]
    public function render()
    {
        return view('livewire.marketplace.home', [
            'categories' => Category::where('is_active', true)->withCount('items')->get(),
            'featuredItems' => Item::public()->latest()->take(8)->get(),
            'topStores' => Store::where('is_public', true)->take(6)->get(),
        ]);
    }
}
