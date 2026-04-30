<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <section class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-1/4 w-1/2 h-full bg-indigo-500 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 -right-1/4 w-1/2 h-full bg-emerald-500 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-[1] mb-6">
                    Find everything,<br>
                    <span class="text-emerald-400">near you.</span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl mb-10 max-w-xl mx-auto">
                    The world's first multi-tenant inventory marketplace. Buy directly from stores in your neighborhood.
                </p>

                <!-- Big Search Bar -->
                <div class="relative group max-w-2xl mx-auto" x-data="{ q: '' }">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <form action="{{ route('marketplace.search') }}" method="GET" class="relative flex items-center bg-white rounded-2xl overflow-hidden p-2">
                        <input type="text" name="q" placeholder="What are you looking for today?" 
                            class="w-full px-6 py-4 text-slate-900 placeholder-slate-400 border-none focus:ring-0 text-lg">
                        <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-indigo-600 transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Shop by Category</h2>
                <a href="{{ route('marketplace.search') }}" class="text-sm font-bold text-indigo-600 hover:underline flex items-center gap-2">
                    Browse all
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($categories as $cat)
                    <a href="{{ route('marketplace.category', $cat->slug) }}" class="group bg-slate-50 rounded-2xl p-6 text-center border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                             <i class="fas fa-{{ $cat->icon }} text-xl text-indigo-500"></i>
                        </div>
                        <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors text-sm tracking-tight">{{ $cat->name }}</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 tracking-widest">{{ $cat->items_count }} Items</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-1">New Arrivals</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">The latest drops from our global network.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredItems as $item)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 group hover:shadow-2xl transition-all duration-500 flex flex-col">
                        <div class="relative aspect-square overflow-hidden bg-slate-100">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @endif
                            <div class="absolute bottom-3 right-3">
                                <div class="bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-black shadow-xl">
                                    ${{ number_format($item->price, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-900 mb-1 truncate text-sm">{{ $item->name }}</h4>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                <span class="text-[10px] font-bold text-slate-400 tracking-tight uppercase tracking-widest">{{ $item->store->name }}</span>
                            </div>
                            <a href="{{ route('marketplace.product', ['item' => $item->slug]) }}" class="block w-full text-center py-2 bg-slate-900 text-white text-[10px] font-black rounded-lg hover:bg-indigo-600 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Top Stores Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Discover Top Stores</h2>
                <p class="text-slate-500">Shop with confidence from verified local businesses around the world.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($topStores as $store)
                    <div class="group relative bg-white border border-slate-200 rounded-[2.5rem] p-8 hover:shadow-2xl hover:shadow-indigo-50 transition-all duration-300">
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center p-3 group-hover:scale-110 transition-transform duration-500">
                                @if($store->logo)
                                    <img src="{{ Storage::url($store->logo) }}" class="max-w-full max-h-full object-contain">
                                @else
                                    <span class="text-3xl font-black text-indigo-500">{{ substr($store->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $store->name }}</h3>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $store->city }}, {{ $store->country }}
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2">
                            {{ $store->description ?? 'Official store partner on the Stockify Marketplace network. Discover exclusive products today.' }}
                        </p>
                        <a href="{{ route('marketplace.store', ['store' => $store->slug ?? $store->tenant_id ?? $store->id]) }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:gap-4 transition-all">
                            Visit Storefront
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="bg-indigo-600 rounded-[3rem] p-12 md:p-20 relative overflow-hidden text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10 max-w-xl">
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-6 tracking-tight">Are you a store owner? Join the marketplace.</h2>
                    <p class="text-indigo-100 text-lg mb-0">List your products and reach thousands of customers in your city.</p>
                </div>
                <div class="relative z-10 shrink-0">
                    <a href="{{ route('tenant.register.post') }}" class="inline-flex px-10 py-5 bg-white text-indigo-600 font-extrabold rounded-2xl text-lg hover:bg-slate-900 hover:text-white transition-all shadow-xl">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
