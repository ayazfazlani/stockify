<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="mb-12 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">My Orders</h1>
                <p class="text-slate-500 font-medium">Manage your marketplace purchases.</p>
            </div>
            <a href="{{ route('marketplace.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">Continue Shopping</a>
        </header>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-[1.5rem] mb-8 flex items-center gap-4">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="font-bold">{{ session('status') }}</span>
            </div>
        @endif

        <div class="space-y-8">
            @forelse($orders as $order)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <!-- Order Header -->
                    <div class="p-6 bg-slate-50 flex flex-wrap items-center justify-between gap-4 border-b border-slate-100">
                        <div class="flex items-center gap-6">
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Order Date</div>
                                <div class="text-sm font-bold text-slate-900">{{ $order->created_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Order #</div>
                                <div class="text-sm font-bold text-slate-900">{{ $order->order_number }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Amount</div>
                                <div class="text-sm font-black text-indigo-600">${{ number_format($order->total_amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest 
                                {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Store Info & Items -->
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center p-1.5 grayscale group-hover:grayscale-0 transition-all">
                                @if($order->store->logo)
                                    <img src="{{ Storage::url($order->store->logo) }}" class="max-w-full max-h-full">
                                @else
                                    <span class="text-xs font-black text-indigo-600">{{ substr($order->store->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Ordered from {{ $order->store->name }}</span>
                        </div>

                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-50 rounded-xl overflow-hidden shrink-0">
                                        @if($item->item->image)
                                            <img src="{{ Storage::url($item->item->image) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-slate-900 truncate">{{ $item->item->name }}</div>
                                        <div class="text-[10px] font-medium text-slate-400">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-sm font-bold text-slate-900">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-100">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="fas fa-box-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No orders found</h3>
                    <p class="text-slate-400 text-sm max-w-xs mx-auto mb-8">You haven't placed any orders yet. Explore the marketplace to find great products.</p>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-indigo-600 transition-all">
                        Browse Marketplace
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
