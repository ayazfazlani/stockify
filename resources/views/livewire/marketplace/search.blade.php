<div class="bg-slate-50 min-h-screen pt-20 pb-20">
    <!-- Header/Search Bar -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <!-- Search Input -->
                <div class="relative flex-grow w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        placeholder="Search for products, brands, or stores..."
                        class="block w-full pl-11 pr-4 py-3 bg-slate-100 border-transparent rounded-2xl text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all">

                    <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Location Button -->
                <button x-data="{ 
                        detectLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(
                                    (position) => {
                                        @this.setLocation(position.coords.latitude, position.coords.longitude, 'Current Location');
                                    },
                                    (error) => {
                                        alert('Unable to retrieve your location. Please check browser permissions.');
                                    }
                                );
                            }
                        }
                    }" @click="detectLocation"
                    class="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium text-slate-700">{{ $userLocationName }}</span>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 shrink-0">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-slate-900">Filters</h2>
                        <button wire:click="$set('search', ''); $set('category', '');"
                            class="text-xs text-indigo-600 font-semibold hover:underline">Reset</button>
                    </div>

                    <!-- Categories -->
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Categories</h3>
                        <div class="space-y-3">
                            <button wire:click="$set('category', '')"
                                class="flex items-center w-full text-left text-sm {{ $category == '' ? 'text-indigo-600 font-bold' : 'text-slate-500' }}">
                                All Categories
                            </button>
                            @foreach($categories as $cat)
                                <a href="{{ route('marketplace.category', $cat->slug) }}"
                                    class="flex items-center w-full text-left text-xs hover:text-indigo-600 transition-colors {{ $category == $cat->slug ? 'text-indigo-600 font-bold' : 'text-slate-500' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Distance -->
                    <div class="mb-6">
                        <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-4">Max Distance</h3>
                        <input type="range" wire:model.live="maxDistance" min="1" max="200" step="5"
                            class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <div class="flex justify-between mt-2 text-[10px] text-slate-400 font-bold">
                            <span>1km</span>
                            <span class="text-indigo-600">{{ $maxDistance }}km</span>
                            <span>200km</span>
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-4">Sort By</h3>
                        <select wire:model.live="sort"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-indigo-100 pb-2">
                            <option value="latest">Latest</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            @if($lat && $lng)
                                <option value="distance">Nearest First</option>
                            @endif
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <main class="flex-grow">
                @if($items->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-300">
                        <div class="mb-4 flex justify-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-search text-3xl text-slate-300"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">No products found</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mt-2 text-sm">Try adjusting your filters or search
                            keywords to find what you're looking for.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($items as $item)
                            <div
                                class="bg-white rounded-2xl overflow-hidden border border-slate-200 group hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300 flex flex-col">
                                <!-- Image Wrapper -->
                                <div class="relative aspect-square overflow-hidden bg-slate-100">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-4xl text-slate-200"></i>
                                        </div>
                                    @endif

                                    <!-- Store Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/90 backdrop-blur shadow-sm text-[9px] font-bold text-slate-700 uppercase tracking-tight">
                                            <i class="fas fa-store text-indigo-500 text-[8px]"></i>
                                            {{ $item->store->name }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4 flex-grow flex flex-col">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4
                                            class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1 text-sm">
                                            {{ $item->name }}</h4>
                                        <span
                                            class="text-indigo-600 font-black text-sm">${{ number_format($item->price, 2) }}</span>
                                    </div>

                                    <p class="text-[11px] text-slate-500 line-clamp-2 mb-4 flex-grow leading-relaxed">
                                        {{ $item->description ?? 'No description available.' }}</p>

                                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-auto">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full {{ $item->quantity > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                            </div>
                                            <span
                                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->quantity > 0 ? 'In Stock' : 'Sold Out' }}</span>
                                        </div>
                                        <a href="{{ route('marketplace.product', $item) }}"
                                            class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-lg hover:bg-indigo-600 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $items->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>