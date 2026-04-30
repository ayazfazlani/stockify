<?php

namespace App\Livewire\Marketplace\Cart;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCanvas extends Component
{
    public $isOpen = false;

    #[On('cart-updated')]
    public function refresh()
    {
        // Simply refresh the view
    }

    public function removeFromCart(CartService $cart, $itemId)
    {
        $cart->removeFromCart($itemId);
        $this->dispatch('cart-updated');
    }

    public function updateQuantity(CartService $cart, $itemId, $quantity)
    {
        $cart->updateQuantity($itemId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function toggle()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function render(CartService $cart)
    {
        return view('livewire.marketplace.cart.cart-canvas', [
            'cartItems' => $cart->getCart(),
            'total' => $cart->getTotal(),
            'count' => $cart->getCount(),
        ]);
    }
}
