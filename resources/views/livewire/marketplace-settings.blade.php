<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Marketplace Presence</h1>
            <p class="text-slate-500">Enable your store on the public marketplace and manage which items are visible to customers.</p>
            
            @php
                $tenant = tenant();
                $hasMarketplaceFeature = $tenant && $tenant->hasFeature(\App\Enums\PlanFeature::MARKETPLACE);
            @endphp

            @if(!$hasMarketplaceFeature)
            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-800">Plan Restriction</h4>
                    <p class="text-xs text-amber-700">Your current plan does not support marketplace visibility. <a href="{{ route('tenant.admin', ['tenant' => $tenantSlug, 'section' => 'billing']) }}" class="font-bold underline italic hover:text-amber-900">Upgrade to unlock</a></p>
                </div>
            </div>
            @endif
        </header>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Store Visibility & Location -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h2 class="font-bold text-slate-900 mb-6">Store Visibility</h2>
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl mb-4">
                        <div>
                            <div class="font-bold text-slate-900">Public Mode</div>
                            <div class="text-xs text-slate-500">Show store in search</div>
                        </div>
                        <button wire:click="$toggle('is_public')" 
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_public ? 'bg-indigo-600' : 'bg-slate-200' }} {{ !$hasMarketplaceFeature ? 'opacity-50 pointer-events-none' : '' }}"
                            @if(!$hasMarketplaceFeature) disabled @endif>
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_public ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    @if($is_public)
                        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                            <p class="text-xs text-emerald-700 font-medium">Your store is now LIVE globally!</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h2 class="font-bold text-slate-900 mb-6">Location Data</h2>
                    <form wire:submit.prevent="updateStoreSettings" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Street Address</label>
                            <input type="text" wire:model="address" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                        </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">City</label>
                                    <input type="text" wire:model="city" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Area / Neighborhood</label>
                                    <input type="text" wire:model="area" placeholder="e.g. Clifton, Saddar, DHA" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Country</label>
                                    <select wire:model.live="country" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c }}">{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Currency (ISO)</label>
                                        <select wire:model="currency" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                                            <option value="">Select Currency</option>
                                            @foreach($this->currencies as $curr)
                                                <option value="{{ $curr }}">{{ $curr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Symbol</label>
                                        <select wire:model="currency_symbol" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                                            <option value="">Select Symbol</option>
                                            @foreach($this->symbols as $sym)
                                                <option value="{{ $sym }}">{{ $sym }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Latitude</label>
                                <input type="text" wire:model="latitude" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Longitude</label>
                                <input type="text" wire:model="longitude" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="button" 
                                onclick="detectLocation()"
                                class="w-full py-2 px-4 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-location-arrow text-xs"></i>
                                <span>Fetch My Coordinates</span>
                            </button>
                            <script>
                                function detectLocation() {
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition(function(position) {
                                            @this.setLocation(position.coords.latitude, position.coords.longitude);
                                        }, function(error) {
                                            alert("Error detecting location: " + error.message);
                                        });
                                    } else {
                                        alert("Geolocation is not supported by this browser.");
                                    }
                                }
                            </script>
                        </div>
                        <button type="submit" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-indigo-600 transition-colors">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Item Visibility -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50">
                        <h2 class="font-bold text-slate-900">Manage Inventory Visibility</h2>
                        <p class="text-xs text-slate-500">Toggle which items are available for public discovery.</p>
                    </div>

                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Item</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Price</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Marketplace Visibility</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($items as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $item->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->sku }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $currency_symbol ?? '$' }}{{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="toggleItemPublic({{ $item->id }})" 
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $item->is_public ? 'bg-emerald-500' : 'bg-slate-200' }} {{ !$hasMarketplaceFeature ? 'opacity-50 pointer-events-none' : '' }}"
                                            @if(!$hasMarketplaceFeature) disabled @endif>
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $item->is_public ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="p-6">
                        {{ $items->links() }}
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-8 bg-red-50 rounded-3xl p-8 border border-red-100">
                    <h2 class="text-xl font-bold text-red-900 mb-2">Danger Zone</h2>
                    <p class="text-sm text-red-700 mb-6 font-medium">Once you delete your store, there is no going back. Please be certain.</p>
                    
                    <button wire:click="confirmDelete" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-100 flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete Store Permanently</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-red-50">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-red-50/50">
                <h3 class="text-lg font-black text-red-600 uppercase tracking-tight">
                    Confirm Deletion
                </h3>
                <button wire:click="$set('showDeleteModal', false)" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-8">
                <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-xs leading-relaxed">
                    <p class="font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        Critical Warning
                    </p>
                    <p>Deleting <strong>{{ $store->name }}</strong> will permanently erase all inventory, order history, and customer data. This action is irreversible.</p>
                </div>
                
                <p class="text-sm text-slate-600 mb-4 font-medium">
                    To proceed, please type your store name: <br>
                    <span class="font-black text-slate-900 select-all">{{ $store->name }}</span>
                </p>
                
                <input type="text" 
                       wire:model.live="confirmName" 
                       class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-red-500 focus:ring-4 focus:ring-red-50 mb-2" 
                       placeholder="Confirm store name">
                
                @error('confirmName')
                    <span class="text-red-500 text-xs block mb-4 font-medium">{{ $message }}</span>
                @enderror
                
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <button wire:click="$set('showDeleteModal', false)" class="w-full py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm">
                        Keep Store
                    </button>
                    <button wire:click="delete" 
                            class="w-full py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-100 text-sm flex items-center justify-center gap-2"
                            {{ $confirmName !== $store->name ? 'disabled' : '' }}
                            @if($confirmName !== $store->name) style="opacity: 0.5; cursor: not-allowed;" @endif>
                        <i class="fas fa-check"></i>
                        <span>Confirm</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
