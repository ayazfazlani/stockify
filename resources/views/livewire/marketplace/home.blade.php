<div class="bg-white min-h-screen">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "StockFlow Marketplace",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('marketplace.search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "StockFlow",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo.png') }}"
    }
    </script>

    <!-- ========================================== -->
    <!-- HERO SECTION                               -->
    <!-- ========================================== -->
    <section class="relative pt-24 md:pt-32 pb-20 md:pb-28 bg-white overflow-hidden">
        <!-- Decorative blurred blobs -->
        <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-100 rounded-full blur-3xl opacity-30 pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-emerald-50 rounded-full blur-3xl opacity-40 pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="700">

                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Global Network of Verified Stores
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.1] tracking-tighter text-slate-900 mb-6">
                    Find everything,<br>
                    <span class="text-indigo-600">near you.</span>
                </h1>

                <p class="text-lg md:text-xl text-slate-500 leading-relaxed mb-10 max-w-xl mx-auto">
                    Access inventory from trusted neighborhood stores through a secure, multi-tenant marketplace platform.
                </p>

                <!-- Search Bar -->
                <div class="relative group max-w-2xl mx-auto" x-data="{ q: '' }">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <form action="{{ route('marketplace.search') }}" method="GET" class="relative flex items-center bg-white rounded-xl overflow-hidden p-1.5 shadow-lg shadow-slate-200/50 border border-slate-100">
                        <svg class="w-5 h-5 text-slate-400 ml-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="q" placeholder="What are you looking for today?" 
                            class="w-full px-4 py-3 text-slate-900 placeholder-slate-400 border-none focus:ring-0 text-base bg-transparent">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold text-sm hover:bg-indigo-700 transition-all active:scale-95 shadow-md shadow-indigo-200/50 shrink-0">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CATEGORIES SECTION                         -->
    <!-- ========================================== -->
    <section class="py-16 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 leading-tight">Shop by Category</h2>
                    <p class="text-slate-500 mt-2">Browse products from verified store partners.</p>
                </div>
                <a href="{{ route('marketplace.search') }}" class="inline-flex items-center text-indigo-600 text-sm font-bold hover:gap-2 transition-all gap-1">
                    Browse all
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @foreach($categories as $cat)
                    <a href="{{ route('marketplace.category', $cat->slug) }}" class="group bg-white rounded-2xl p-6 text-center border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-100 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors text-sm">{{ $cat->name }}</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">{{ $cat->items_count }} Items</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- FEATURED PRODUCTS SECTION                  -->
    <!-- ========================================== -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
                <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
                    New Arrivals
                </h2>
                <p class="text-lg text-slate-500 leading-relaxed">
                    The latest inventory from verified store partners.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredItems as $item)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50/30 transition-all duration-300 flex flex-col">
                        <div class="relative aspect-square overflow-hidden bg-slate-50">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute bottom-3 right-3">
                                <div class="bg-white text-slate-900 px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg border border-slate-100">
                                    {{ $item->store->currency_symbol ?? config('app.currency_symbol') }}{{ number_format($item->price, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <h4 class="font-semibold text-slate-900 mb-2 truncate text-[15px]">{{ $item->name }}</h4>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    {{ $item->store->name }}
                                </span>
                            </div>
                            <a href="{{ route('marketplace.product', ['item' => $item->slug]) }}" class="mt-auto block w-full text-center py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-indigo-600 transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CITIES SECTION                             -->
    <!-- ========================================== -->
    <section class="py-16 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-aos="fade-up">
                <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">Local Focus</h2>
                <p class="text-lg text-slate-500">Find amazing stores in your city.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                @foreach($cities as $city)
                    <a href="{{ route('marketplace.stores', ['city' => $city]) }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-300 shadow-sm hover:shadow-md active:scale-95">
                        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $city }}
                    </a>
                @endforeach
                <a href="{{ route('marketplace.stores') }}" class="px-5 py-2.5 bg-indigo-50 border border-indigo-100 rounded-full text-sm font-medium text-indigo-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-300 shadow-sm active:scale-95">
                    View All Cities
                </a>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- TOP STORES SECTION                         -->
    <!-- ========================================== -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
                <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
                    Discover Top Stores
                </h2>
                <p class="text-lg text-slate-500 leading-relaxed">
                    Shop with confidence from verified local businesses around the world.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topStores as $store)
                    <div class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50/30 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-indigo-100 group-hover:scale-105 transition-all duration-300">
                                @if($store->logo)
                                    <img src="{{ Storage::url($store->logo) }}" class="max-w-full max-h-full object-contain p-2">
                                @else
                                    <span class="text-xl font-bold text-indigo-600">{{ substr($store->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $store->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wide">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        Verified
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $store->city }}, {{ $store->country }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed mb-5 line-clamp-2">
                            {{ $store->description ?? 'Official store partner on the Stockify Marketplace network. Discover exclusive products today.' }}
                        </p>
                        @php
                            $storeRoute = filled($store->slug) ? $store->slug : ($store->tenant_id ?? $store->id);
                        @endphp
                        <a href="{{ route('marketplace.search', ['store' => $storeRoute]) }}" class="inline-flex items-center gap-2 text-indigo-600 text-sm font-semibold hover:gap-3 transition-all">
                            Visit Storefront
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CTA SECTION                                -->
    <!-- ========================================== -->
    <section class="py-24 bg-slate-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-white mb-5 leading-tight" data-aos="fade-up">
                Are you a store owner?<br>Join the marketplace.
            </h2>
            <p class="text-lg text-slate-400 mb-10 max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                List your products and reach thousands of customers in your city.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('tenant.register.post') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-indigo-600 text-white font-semibold text-[15px] shadow-lg shadow-indigo-900/50 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all duration-200">
                    Get Started Free
                </a>
                <a href="#" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-slate-800 text-white font-semibold text-[15px] border border-slate-700 hover:bg-slate-700 hover:-translate-y-0.5 transition-all duration-200">
                    Learn More
                </a>
            </div>
        </div>
    </section>
</div>