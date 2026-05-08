<div>
    @push('seo')
        <title>{{ $store->name }} | POS for Shops Marketplace</title>
        <meta name="description" content="{{ $store->description }}">
    @endpush

    <div class="bg-gray-50 min-h-screen">
        <!-- Facebook-like Profile Header -->
        <div class="bg-white shadow-sm border-b overflow-hidden">
            <div class="max-w-6xl mx-auto relative mt-16 md:mt-20">
                <!-- Banner Area -->
                <div class="relative h-48 md:h-80 bg-teal-800 rounded-b-xl overflow-hidden group">
                    @if($store->banner)
                        <img src="{{ Storage::url($store->banner) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full gradient-bg-teal flex items-center justify-center">
                            <i class="fas fa-store text-white/20 text-6xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>

                <!-- Profile Info Bar -->
                <div class="px-4 pb-4">
                    <div
                        class="relative flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-24 mb-4 md:space-x-6">
                        <!-- Profile Image (Logo) -->
                        <div class="relative">
                            <div
                                class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white bg-white overflow-hidden shadow-lg">
                                @if($store->logo)
                                    <img src="{{ Storage::url($store->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gray-100 flex items-center justify-center text-teal-500 text-4xl">
                                        {{ substr($store->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left mt-4 md:mt-0 md:pb-4">
                            <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 drop-shadow-sm">
                                {{ $store->name }}</h1>
                            <p
                                class="text-sm font-medium text-gray-500 mt-1 flex items-center justify-center md:justify-start">
                                <i class="fas fa-map-marker-alt mr-2 text-teal-500"></i>
                                {{ $store->area ? $store->area . ', ' : '' }}{{ $store->city }}, {{ $store->country }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="hidden md:flex space-x-2 md:pb-4">
                            <button
                                class="bg-teal-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-600 transition shadow-sm">
                                <i class="fas fa-share-alt mr-2"></i> Share
                            </button>
                            <button
                                class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-200 transition">
                                <i class="fas fa-info-circle mr-2"></i> About
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="max-w-3xl border-t pt-4">
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed italic">
                            "{{ $store->description ?? 'Welcome to our official marketplace shop. Discover our verified inventory below.' }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Available Products</h2>
                    <p class="text-xs text-gray-500">Showing all public inventory from {{ $store->name }}</p>
                </div>

                <div class="relative w-full md:w-72">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm shadow-sm"
                        placeholder="Search in this store...">
                    <i class="fas fa-search absolute left-3.5 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse($items as $item)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover-lift transition group">
                        <div class="relative aspect-square">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-teal-50 flex items-center justify-center">
                                    <i class="fas fa-box text-teal-200 text-3xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-white/90 px-2 py-1 rounded text-[10px] font-bold text-teal-600 shadow-sm">
                                    {{ $store->currency_symbol }}{{ number_format($item->price, 0) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-3">
                            <h4 class="font-bold text-gray-800 text-sm line-clamp-1 truncate">{{ $item->name }}</h4>
                            <p class="text-[10px] text-gray-400 truncate mb-3">SKU: {{ $item->sku }}</p>
                            <a href="{{ route('marketplace.product', $item->slug) }}"
                                class="block text-center bg-teal-500 text-white py-2 rounded-lg text-xs font-bold hover:bg-teal-600 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No products found matching your search.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>