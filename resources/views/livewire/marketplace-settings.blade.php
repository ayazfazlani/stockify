<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Marketplace Presence</h1>
            <p class="text-slate-500">Enable your store on the public marketplace and manage which items are visible to customers.</p>
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
                        <button wire:click="$toggle('is_public')" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_public ? 'bg-indigo-600' : 'bg-slate-200' }}">
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
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">City</label>
                                <input type="text" wire:model="city" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Country</label>
                                <input type="text" wire:model="country" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Latitude</label>
                                <input type="text" wire:model="latitude" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Longitude</label>
                                <input type="text" wire:model="longitude" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50">
                            </div>
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
                                        <div class="font-bold text-slate-900">${{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="toggleItemPublic({{ $item->id }})" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $item->is_public ? 'bg-emerald-500' : 'bg-slate-200' }}">
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
            </div>
        </div>
    </div>
</div>
