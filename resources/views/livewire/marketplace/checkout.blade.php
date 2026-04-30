<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="mb-12">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Secure Checkout</h1>
            <p class="text-slate-500 font-medium">Complete your purchase from the marketplace network.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left: Checkout Forms -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipping Info -->
                <section class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900">Shipping Information</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Delivery Address</label>
                            <textarea wire:model="shipping_address" rows="3" placeholder="Enter your full street address, city, and zip code..."
                                class="w-full bg-slate-50 border-slate-100 rounded-2xl text-sm p-4 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all"></textarea>
                            @error('shipping_address') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <!-- Payment Method -->
                <section class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900">Payment Method</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all {{ $payment_method === 'cod' ? 'border-indigo-600 bg-indigo-50/50' : 'border-slate-100 hover:bg-slate-50' }}">
                            <input type="radio" wire:model="payment_method" value="cod" class="hidden">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-hand-holding-dollar text-slate-900"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">Cash on Delivery</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Pay when you receive</div>
                            </div>
                            @if($payment_method === 'cod')
                                <i class="fas fa-check-circle text-indigo-600 absolute top-4 right-4 animate-bounce"></i>
                            @endif
                        </label>

                        <label class="relative flex items-center p-4 border rounded-2xl cursor-not-allowed opacity-50 bg-slate-50">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-university text-slate-400"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-400 text-sm">Online Payment</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Coming Soon</div>
                            </div>
                        </label>
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white sticky top-24 shadow-2xl shadow-indigo-200">
                    <h2 class="text-xl font-black mb-8 border-b border-white/10 pb-6">Order Summary</h2>

                    <div class="space-y-6 mb-10 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cartItems as $item)
                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-white/5 rounded-xl flex-shrink-0 overflow-hidden">
                                    @if($item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-sm truncate">{{ $item['name'] }}</div>
                                    <div class="text-[10px] text-white/40 font-bold uppercase tracking-widest">{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</div>
                                </div>
                                <div class="font-bold text-sm">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 border-t border-white/10 pt-8 mb-8">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/50 font-bold uppercase tracking-widest text-[10px]">Subtotal</span>
                            <span class="font-bold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/50 font-bold uppercase tracking-widest text-[10px]">Shipping</span>
                            <span class="text-emerald-400 font-bold text-[10px] uppercase">Free</span>
                        </div>
                        <div class="flex justify-between items-center pt-4">
                            <span class="text-lg font-black tracking-tight">Total</span>
                            <span class="text-3xl font-black text-indigo-400 font-mono tracking-tighter">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button wire:click="placeOrder" wire:loading.attr="disabled" class="w-full py-5 bg-indigo-500 hover:bg-emerald-500 text-white font-black rounded-2xl transition-all duration-300 shadow-xl shadow-indigo-900 group">
                        <span wire:loading.remove>Confirm & Place Order</span>
                        <span wire:loading><i class="fas fa-spinner fa-spin mr-2"></i>Processing...</span>
                    </button>

                    <p class="text-center text-[10px] text-white/30 mt-6 font-medium italic lowercase tracking-widest">
                        Prices include all applicable taxes and fees.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
