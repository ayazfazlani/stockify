@push('seo')
    <title>{{ $item->name }} | Stockify Marketplace</title>
    <meta name="description" content="{{ Str::limit($item->description, 160) }}">
    <meta property="og:title" content="{{ $item->name }}">
    <meta property="og:description" content="{{ Str::limit($item->description, 160) }}">
    @if($item->image)
        <meta property="og:image" content="{{ Storage::url($item->image) }}">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

<div class="bg-white min-h-screen pt-20 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm font-medium text-slate-500">
            <a href="{{ route('marketplace.index') }}" class="hover:text-indigo-600 transition-colors">Marketplace</a>
            <span class="mx-3 text-slate-300">/</span>
            <a href="{{ route('marketplace.index', ['cat' => $item->category?->slug]) }}"
                class="hover:text-indigo-600 transition-colors">{{ $item->category?->name ?? 'Uncategorized' }}</a>
            <span class="mx-3 text-slate-300">/</span>
            <span class="text-slate-900 truncate">{{ $item->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Left: Image Gallery -->
            <div class="space-y-6">
                <div
                    class="aspect-square rounded-[2.5rem] overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center relative group">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg class="w-32 h-32 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif

                    <div class="absolute top-6 right-6">
                        <button
                            class="w-12 h-12 bg-white/90 backdrop-blur rounded-2xl shadow-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Product Badges -->
                <div class="flex flex-wrap gap-3">
                    <span
                        class="px-4 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        Available in Stock
                    </span>
                    <span class="px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl">
                        Free delivery from this store
                    </span>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="flex flex-col">
                <div class="mb-5">
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-tight mb-2">
                        {{ $item->name }}
                    </h1>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center text-amber-400">
                            <i class="fas fa-star text-[10px]"></i>
                            <i class="fas fa-star text-[10px]"></i>
                            <i class="fas fa-star text-[10px]"></i>
                            <i class="fas fa-star text-[10px]"></i>
                            <i class="fas fa-star text-[10px]"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono tracking-widest uppercase">SKU:
                            {{ $item->sku }}</span>
                    </div>
                </div>

                <div class="mb-6 pb-6 border-b border-slate-100">
                    <div class="text-2xl font-black text-slate-900 mb-3">
                        ${{ number_format($item->price, 2) }}
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-xl">
                        {{ $item->description ?? 'No detailed description provided for this product. Elevate your inventory management with professional-grade monitoring.' }}
                    </p>
                </div>

                <!-- Store Card -->
                <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 group">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center p-2">
                            @if($item->store->logo)
                                <img src="{{ Storage::url($item->store->logo) }}" alt="{{ $item->store->name }}"
                                    class="max-w-full max-h-full object-contain">
                            @else
                                <div class="text-indigo-600 font-black text-lg">{{ substr($item->store->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Sold by {{ $item->store->name }}</h3>
                            <div
                                class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                <i class="fas fa-location-dot"></i>
                                {{ $item->store->city }}, {{ $item->store->country }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('marketplace.store', ['store' => $item->store->slug ?? $item->store->tenant_id ?? $item->store->id]) }}"
                        class="block w-full text-center py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                        Visit Store Page
                    </a>
                </div>

                <div class="mt-auto space-y-4">
                    @livewire('marketplace.cart.add-to-cart', ['item' => $item])

                    <div class="flex items-center justify-center gap-4 py-4 border-t border-slate-50">
                        <div
                            class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <i class="fas fa-shield-halved text-emerald-500"></i>
                            Secure Checkout
                        </div>
                        <div
                            class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <i class="fas fa-truck text-emerald-500"></i>
                            Fast Delivery
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-32">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">More from {{ $item->category?->name }}</h2>
                <a href="{{ route('marketplace.index', ['cat' => $item->category?->slug]) }}"
                    class="text-sm font-bold text-indigo-600 hover:underline">See all</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedItems as $rel)
                    <a href="{{ route('marketplace.product', $rel->slug) }}" class="group block">
                        <div class="aspect-square rounded-[2rem] overflow-hidden bg-slate-50 mb-4 border border-slate-100">
                            @if($rel->image)
                                <img src="{{ Storage::url($rel->image) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                        </div>
                        <h4 class="font-bold text-slate-900 line-clamp-1 mb-1">{{ $rel->name }}</h4>
                        <p class="text-indigo-600 font-extrabold text-lg">${{ number_format($rel->price, 2) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>