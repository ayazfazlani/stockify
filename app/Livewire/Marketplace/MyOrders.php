<?php

namespace App\Livewire\Marketplace;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MyOrders extends Component
{
    #[Layout('components.layouts.marketplace')]
    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['store', 'items.item'])
            ->latest()
            ->get();

        return view('livewire.marketplace.my-orders', [
            'orders' => $orders,
        ]);
    }
}
