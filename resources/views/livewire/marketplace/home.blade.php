<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <section class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-1/4 w-1/2 h-full bg-indigo-500 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 -right-1/4 w-1/2 h-full bg-emerald-500 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full mb-6">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Global Network of Verified Stores</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4">
                    Find everything,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">near you.</span>
                </h1>
                <p class="text-slate-400 text-base md:text-lg mb-8 max-w-xl mx-auto leading-relaxed">
                    Access inventory from trusted neighborhood stores through a secure, multi-tenant marketplace platform.
                </p>

                <!-- Big Search Bar -->
                <div class="relative group max-w-2xl mx-auto" x-data="{ q: '' }">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <form action="{{ route('marketplace.search') }}" method="GET" class="relative flex items-center bg-white rounded-2xl overflow-hidden p-1.5 shadow-2xl">
                        <input type="text" name="q" placeholder="What are you looking for today?" 
                            class="w-full px-5 py-3 text-slate-900 placeholder-slate-400 border-none focus:ring-0 text-base">
                        <button type="submit" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-black/10 shrink-0">
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
                    <p class="premium-label">The latest inventory from verified store partners.</p>
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
                                    {{ $item->store->currency_symbol ?? config('app.currency_symbol') }}{{ number_format($item->price, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-900 mb-1 truncate text-sm">{{ $item->name }}</h4>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="verified-badge">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    {{ $item->store->name }}
                                </span>
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

    <!-- Cities Section -->
    <section class="py-16 bg-white border-t border-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-1">Local Focus</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Find amazing stores in your city.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($cities as $city)
                    <a href="{{ route('marketplace.stores', ['city' => $city]) }}" class="px-5 py-2 bg-slate-50 border border-slate-100 rounded-full text-xs font-bold text-slate-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                        <i class="fas fa-map-marker-alt mr-2 opacity-50"></i>{{ $city }}
                    </a>
                @endforeach
                <a href="{{ route('marketplace.stores') }}" class="px-5 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-bold text-indigo-600 hover:bg-slate-900 hover:text-white transition-all duration-300 active:scale-95">
                    View All Cities
                </a>
            </div>
        </div>
    </section>

    <!-- Top Stores Section -->
    <section class="py-24 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-4 tracking-tight">Discover Top Stores</h2>
                <p class="text-slate-500 text-sm">Shop with confidence from verified local businesses around the world.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($topStores as $store)
                    <div class="group relative bg-white border border-slate-200 rounded-[2.5rem] p-8 hover:shadow-2xl hover:shadow-indigo-50 transition-all duration-300">
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center p-3 group-hover:scale-110 transition-transform duration-500">
                                @if($store->logo)
                                    <img src="{{ Storage::url($store->logo) }}" class="max-w-full max-h-full object-contain">
                                @else
                                    <span class="text-2xl font-black text-indigo-500">{{ substr($store->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $store->name }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="verified-badge">Verified Partner</span>
                                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $store->city }}, {{ $store->country }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs leading-relaxed mb-8 line-clamp-2 h-8">
                            {{ $store->description ?? 'Official store partner on the Stockify Marketplace network. Discover exclusive products today.' }}
                        </p>
                        @php
                            $storeRoute = filled($store->slug) ? $store->slug : ($store->tenant_id ?? $store->id);
                        @endphp
                        <a href="{{ route('marketplace.search', ['store' => $storeRoute]) }}" class="inline-flex items-center gap-2 text-indigo-600 text-xs font-bold hover:gap-4 transition-all">
                            Visit Storefront
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
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
                    <h2 class="text-2xl md:text-4xl font-black text-white leading-tight mb-6 tracking-tight">Are you a store owner? Join the marketplace.</h2>
                    <p class="text-indigo-100 text-lg mb-0">List your products and reach thousands of customers in your city.</p>
                </div>
                <div class="relative z-10 shrink-0">
                    <a href="{{ route('tenant.register.post') }}" class="inline-flex px-8 py-4 bg-white text-indigo-600 font-black rounded-2xl text-base hover:bg-slate-900 hover:text-white transition-all shadow-xl hover:-translate-y-1">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
