<div>
    @push('seo')
        <title>Browse Stores | StockFlow Marketplace</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endpush
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "Marketplace Stores",
      "description": "Discover local verified stores and shop their inventory directly.",
      "url": "{{ url()->current() }}"
    }
    </script>
    
    <div class="bg-gray-50 min-h-screen pt-20">
        <div class="gradient-bg-teal py-10 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 animate-fade-in-down">Discover Local Stores</h1>
                <p class="text-teal-50 opacity-90 max-w-2xl mx-auto text-sm md:text-base">Find the best local businesses
                    in your city and shop directly from their verified inventory.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="w-full lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-sliders-h mr-2 text-teal-500"></i> Filters
                        </h2>
                        <div class="mb-6">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Search
                                Store</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all duration-300"
                                    placeholder="Store name...">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">City
                                Filter</label>
                            <select wire:model.live="city"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm appearance-none cursor-pointer">
                                <option value="">All Cities</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Country
                                Filter</label>
                            <select wire:model.live="country"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm appearance-none cursor-pointer">
                                <option value="">All Countries</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button wire:click="$set('city', ''); $set('country', ''); $set('search', '')"
                            class="w-full py-2 text-xs font-medium text-gray-500 hover:text-teal-600 transition-colors">
                            Clear All Filters
                        </button>
                    </div>
                </aside>
                <main class="flex-1">
                    <div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center">
                                <i class="fas fa-map-marked-alt mr-2 text-teal-500"></i> Interactive Store Map
                            </h3>
                            <span class="text-xs text-gray-400">{{ count($markers) }} locations found</span>
                        </div>
                        <div id="store-map" wire:ignore class="h-72 w-full"></div>
                    </div>
                    <div class="mb-6 flex justify-between items-center text-sm">
                        <p class="text-gray-500">Showing <span
                                class="text-gray-900 font-bold font-mono">{{ $stores->total() }}</span> stores</p>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-400">Sort:</span>
                            <select class="text-xs font-medium bg-transparent border-none focus:ring-0 cursor-pointer">
                                <option>Popularity</option>
                                <option>Alphabetical</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($stores as $store)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover-lift transition-all duration-300 group">
                                <div class="relative h-40">
                                    @if($store->logo)
                                        <img src="{{ Storage::url($store->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-teal-50 flex items-center justify-center">
                                            <i class="fas fa-store text-teal-200 text-3xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 left-2">
                                        <span
                                            class="bg-white/90 px-2 py-1 rounded text-[10px] font-bold text-teal-600 uppercase">{{ $store->city }}</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-900 mb-1">{{ $store->name }}</h4>
                                    <p class="text-xs text-gray-500 line-clamp-2 h-8 mb-4">{{ $store->description }}</p>
                                    <div class="flex items-center justify-between border-t pt-3">
                                        <span class="text-[10px] text-gray-400">Verified Partner</span>
                                        <a href="{{ route('marketplace.search', ['store' => $store->slug]) }}"
                                            class="bg-teal-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold">Browse</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center text-gray-500">No stores found.</div>
                        @endforelse
                    </div>
                    <div class="mt-8">{{ $stores->links() }}</div>
                </main>
            </div>
        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            gap: 0.25rem;
        }

        .page-item {
            border: 1px solid #eee;
            border-radius: 0.25rem;
        }

        .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .active .page-link {
            background: #14b8a6;
            color: #fff;
        }
    </style>
</div>
<script>
    document.addEventListener('livewire:navigated', () => initMap());
    document.addEventListener('DOMContentLoaded', () => initMap());
    let map;
    let markers = [];
    function initMap() {
        const data = @json($markers);
        const container = document.getElementById('store-map');
        if (!container) return;
        if (map) map.remove();
        const center = data.length > 0 ? [data[0].lat, data[0].lng] : [30.3753, 69.3451];
        map = L.map('store-map').setView(center, data.length > 0 ? 10 : 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `<div class="bg-teal-500 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center text-white"><i class="fas fa-store text-[10px]"></i></div>`,
            iconSize: [24, 24]
        });
        data.forEach(m => {
            L.marker([m.lat, m.lng], { icon }).addTo(map).bindPopup(`<h5 class="font-bold text-xs">${m.name}</h5><p class="text-[10px]">${m.city}</p>`);
        });
    }
</script>