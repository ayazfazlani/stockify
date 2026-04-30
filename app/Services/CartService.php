<?php

namespace App\Services;

use App\Models\Item;

class CartService
{
    public function getCart()
    {
        return session()->get('marketplace_cart', []);
    }

    public function addToCart(Item $item, $quantity = 1)
    {
        $cart = $this->getCart();
        $id = $item->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $quantity,
                'store_id' => $item->store_id,
                'store_name' => $item->store->name,
                'image' => $item->image,
                'slug' => $item->slug,
            ];
        }

        session()->put('marketplace_cart', $cart);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = max(1, $quantity);
            session()->put('marketplace_cart', $cart);
        }
    }

    public function removeFromCart($itemId)
    {
        $cart = $this->getCart();
        unset($cart[$itemId]);
        session()->put('marketplace_cart', $cart);
    }

    public function clear()
    {
        session()->forget('marketplace_cart');
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    public function getCount()
    {
        $count = 0;
        foreach ($this->getCart() as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }
}
