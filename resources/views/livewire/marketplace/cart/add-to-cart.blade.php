<div class="flex items-center gap-2">
    <div class="flex items-center bg-slate-100 rounded-lg p-1 border border-slate-200">
        <button wire:click="$decrement('quantity')" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors" {{ $quantity <= 1 ? 'disabled' : '' }}>
            <i class="fas fa-minus text-[10px]"></i>
        </button>
        <input type="number" wire:model="quantity" class="w-10 bg-transparent border-none text-center text-sm font-bold p-0 focus:ring-0" min="1">
        <button wire:click="$increment('quantity')" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fas fa-plus text-[10px]"></i>
        </button>
    </div>
    <button wire:click="addToCart" class="flex-1 bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-indigo-600 transition-all flex items-center justify-center gap-2">
        <i class="fas fa-cart-shopping text-xs"></i>
        Add to Cart
    </button>
</div>
