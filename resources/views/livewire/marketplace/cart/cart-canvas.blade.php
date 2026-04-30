<div>
    <!-- Cart Trigger Button (Floating or in Header) -->
    <button wire:click="toggle" class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors">
        <i class="fas fa-cart-shopping text-xl"></i>
        @if($count > 0)
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                {{ $count }}
            </span>
        @endif
    </button>

    <!-- Overlay -->
    <div x-data="{ open: @entangle('isOpen') }" 
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden" 
         style="display: none;">
        
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>

        <div class="absolute inset-y-0 right-0 max-w-full flex">
            <div x-show="open"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col h-full rounded-l-[2rem]">
                
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Your Cart</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $count }} Items Selected</p>
                    </div>
                    <button @click="open = false" class="p-2 bg-slate-100 rounded-full text-slate-400 hover:text-slate-900 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Items List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    @forelse($cartItems as $id => $item)
                        <div class="flex gap-4 group">
                            <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden shrink-0">
                                @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-slate-900 truncate pr-4 text-sm">{{ $item['name'] }}</h4>
                                    <button wire:click="removeFromCart({{ $id }})" class="text-slate-300 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">
                                    Sold by {{ $item['store_name'] }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200 scale-90 -ml-2">
                                        <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                                            <i class="fas fa-minus text-[8px]"></i>
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold">{{ $item['quantity'] }}</span>
                                        <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                                            <i class="fas fa-plus text-[8px]"></i>
                                        </button>
                                    </div>
                                    <div class="font-black text-slate-900 text-sm">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center py-20">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                                <i class="fas fa-cart-shopping text-4xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-2">Cart is empty</h3>
                            <p class="text-slate-400 text-sm max-w-[200px]">Looks like you haven't added anything yet.</p>
                            <button @click="open = false" class="mt-8 text-indigo-600 font-bold text-sm hover:underline">Start Shopping</button>
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                @if($count > 0)
                    <div class="p-8 bg-white border-t border-slate-100 rounded-t-[2rem] shadow-[0_-20px_50px_rgba(0,0,0,0.05)]">
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Subtotal</div>
                            <div class="text-2xl font-black text-slate-900">${{ number_format($total, 2) }}</div>
                        </div>
                        <a href="{{ route('marketplace.checkout') }}" class="block w-full bg-slate-900 text-white text-center py-4 rounded-2xl font-bold hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-100">
                            Checkout Now
                        </a>
                        <p class="text-[10px] text-center text-slate-400 mt-4 font-medium italic">
                            Shipping and taxes calculated at checkout.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
