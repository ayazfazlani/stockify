<?php

namespace App\Livewire\Marketplace\Cart;

use App\Models\Item;
use App\Services\CartService;
use Livewire\Component;

class AddToCart extends Component
{
    public Item $item;

    public $quantity = 1;

    public function addToCart(CartService $cart)
    {
        $cart->addToCart($this->item, $this->quantity);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', ['message' => 'Item added to cart!']);
    }

    public function render()
    {
        return view('livewire.marketplace.cart.add-to-cart');
    }
}
