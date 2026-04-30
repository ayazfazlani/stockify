<?php

namespace App\Livewire\Marketplace;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Checkout extends Component
{
    public $shipping_address;

    public $payment_method = 'cod'; // Default Cash on Delivery

    public function mount(CartService $cart)
    {
        if ($cart->getCount() === 0) {
            return redirect()->route('marketplace.index');
        }

        if (Auth::check()) {
            // Pre-fill address if available (for now just a placeholder)
        }
    }

    public function placeOrder(CartService $cart)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        $this->validate([
            'shipping_address' => 'required|min:10',
            'payment_method' => 'required',
        ]);

        $cartItems = $cart->getCart();

        // Group items by store_id
        $groupedItems = [];
        foreach ($cartItems as $item) {
            $groupedItems[$item['store_id']][] = $item;
        }

        DB::beginTransaction();
        try {
            foreach ($groupedItems as $storeId => $items) {
                $storeTotal = 0;
                foreach ($items as $item) {
                    $storeTotal += $item['price'] * $item['quantity'];
                }

                $order = Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                    'user_id' => Auth::id(),
                    'store_id' => $storeId,
                    'total_amount' => $storeTotal,
                    'status' => 'pending',
                    'shipping_address' => $this->shipping_address,
                    'payment_method' => $this->payment_method,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            DB::commit();
            $cart->clear();

            session()->flash('status', 'Your order has been placed successfully!');

            return redirect()->route('marketplace.my-orders');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    #[Layout('components.layouts.marketplace')]
    public function render(CartService $cart)
    {
        return view('livewire.marketplace.checkout', [
            'cartItems' => $cart->getCart(),
            'total' => $cart->getTotal(),
        ]);
    }
}
