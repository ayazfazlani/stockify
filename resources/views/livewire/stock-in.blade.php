<div class="p-6 max-h-screen w-full overflow-y-auto bg-white text-gray-900">
    <!-- Notifications -->
    @if(session()->has('message'))
    <div class="p-4 mb-4 text-sm text-white bg-green-500 rounded-lg" role="alert">
        {{ session('message') }}
    </div>
    @elseif(session()->has('success'))
    <div class="p-4 mb-4 text-sm text-white bg-blue-500 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
    @elseif(session()->has('error'))
    <div class="p-4 mb-4 text-sm text-white bg-red-500 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Stock in</h1>
        <div class="flex gap-2">
            <button wire:click="loadItems" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">
                Refresh
            </button>
        </div>
    </div>

    <!-- Main Scanner (for Selection) -->
    @feature('barcode-scanning')
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Scan to auto-select product</h3>
        <livewire:qr-scanner :scannerId="'selection-scanner'" />
    </div>
    @else
    <div class="mb-6 p-4 rounded-lg border border-amber-200 bg-amber-50 text-sm text-amber-900">
        Camera barcode scanning is not included in your current plan. You can still search and select products below.
    </div>
    @endfeature

    <div class="flex items-center gap-4 mb-6 max-sm:flex-wrap">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" 
                placeholder="Search items by name or SKU..."
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-full flex justify-between md:justify-end md:flex-1 items-center gap-2">
            <input type="date" wire:model.live="dateRange.start" 
                class="p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 max-w-36">
            <span class="text-gray-500">to</span>
            <input type="date" wire:model.live="dateRange.end" 
                class="p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 max-w-36">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-sm:flex-wrap">
        <!-- Left Column: Stock Operations -->
        <div class="space-y-6">
            <!-- Stock In Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold">Stock In Operations</h2>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Item Selection -->
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium">Select Items</h3>
                            @can('create items')
                            <button wire:click="$set('isModalOpen', true)" 
                                class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 shadow-sm">
                                <i class="fas fa-plus"></i> New Item
                            </button>
                            @endcan
                        </div>
                        <ul class="space-y-2 max-h-96 overflow-auto">
                            @foreach($items as $item)
                            <li wire:key="item-{{ $item->id }}" wire:click="toggleItemSelection({{ $item->id }})" 
                                class="p-3 border rounded-md cursor-pointer hover:bg-gray-50 {{ in_array($item->id, array_column($selectedItems, 'id')) ? 'bg-blue-50 border-blue-200' : '' }}">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" class="w-10 h-10 object-cover rounded-md">
                                        @endif
                                        <span>{{ $item->name }} ({{ $item->sku }})</span>
                                    </div>
                                    <span class="text-sm {{ $item->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Qty: {{ $item->quantity }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Selected Items -->
                    @if(count($selectedItems) > 0)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-medium mb-3">Selected for Stock In</h3>
                        <ul class="space-y-2">
                            @foreach($selectedItems as $index => $item)
                            <li wire:key="selected-{{ $item['id'] }}" class="flex justify-between items-center p-2 border rounded-md bg-gray-50">
                                <div class="flex items-center gap-3">
                                    @if($item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" class="w-8 h-8 object-cover rounded-md">
                                    @endif
                                    <span>{{ $item['name'] }}</span>
                                </div>
                                <input type="number" min="1" 
                                    wire:model.debounce.300ms="selectedItems.{{ $index }}.quantity" 
                                    class="w-24 px-2 py-1 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Items: {{ count($selectedItems) }} | 
                                Qty: {{ array_sum(array_column($selectedItems, 'quantity')) }}
                            </div>
                            @can('manage stock')
                            <button wire:click="handleStockIn" 
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 shadow-sm">
                                <i class="fas fa-arrow-down"></i> Stock In
                            </button>
                            @endcan
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Transaction History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold">Stock In History</h2>
            </div>
            <div class="p-4">
                <div class="overflow-auto max-h-[500px]">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Item</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantity</th>
                                @can('view financial metrics')
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Unit Price</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr wire:key="trans-{{ $transaction->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($transaction->date)->format('M d Y') }}</td>
                                <td class="px-4 py-2 text-sm">{{ $transaction->item_name }}</td>
                                <td class="px-4 py-2 text-sm text-green-600 font-semibold">{{ $transaction->quantity }}</td>
                                @can('view financial metrics')
                                <td class="px-4 py-2 text-sm font-mono">${{ number_format($transaction->unit_price, 2) }}</td>
                                <td class="px-4 py-2 text-sm font-mono">${{ number_format($transaction->total_price, 2) }}</td>
                                @endcan
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No stock-in transactions found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Item Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-md rounded-lg shadow-xl overflow-y-auto max-h-[90vh]">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold">Add New Item</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="border rounded-md p-3 bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product SKU / Barcode</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" wire:model="newItem.sku" placeholder="SKU" 
                            class="flex-1 p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @feature('barcode-scanning')
                        <button type="button" wire:click="$toggle('isScanningForSku')" class="px-3 py-1 bg-gray-800 text-white rounded-md text-xs">
                            {{ $isScanningForSku ? 'Hide Scanner' : 'Scan SKU' }}
                        </button>
                        @endfeature
                    </div>
                    @feature('barcode-scanning')
                    @if($isScanningForSku)
                        <div class="mt-2 border-t pt-2">
                            <livewire:qr-scanner :scannerId="'modal-scanner'" />
                        </div>
                    @endif
                    @endfeature
                    @error('newItem.sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tracking Type</label>
                    <select wire:model.live="newItem.tracking_type" class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="standard">Standard (Bulk)</option>
                        <option value="serialized">Serialized (Unique Barcodes)</option>
                    </select>
                </div>
    
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" wire:model="newItem.name" placeholder="Name" 
                        class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('newItem.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Barcodes (optional)</label>
                    <input type="text" wire:model="additionalCodes" placeholder="Comma separated, e.g. 890123, 890124"
                        class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Any barcode here will scan to this same product and quantity.</p>
                    @error('additionalCodes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                        <input type="number" wire:model="newItem.cost" placeholder="Cost" 
                            class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('newItem.cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" wire:model="newItem.price" placeholder="Price" 
                            class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('newItem.price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
    
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <input type="text" wire:model="newItem.type" placeholder="Type" 
                            class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('newItem.type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <input type="text" wire:model="newItem.brand" placeholder="Brand" 
                            class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('newItem.brand') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
    
                @if($newItem['tracking_type'] === 'standard')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Quantity</label>
                    <input type="number" wire:model="newItem.quantity" placeholder="Quantity" 
                        class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('newItem.quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @else
                <div class="border rounded-md p-3 bg-blue-50">
                    <label class="block text-sm font-medium text-blue-800 mb-1 font-bold">Rapid Serial Scan</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" wire:model="currentSerial" 
                            wire:keydown.enter.prevent="addSerial"
                            placeholder="Scan or type serial..." 
                            class="flex-1 p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" wire:click="addSerial" class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs">
                            Add
                        </button>
                    </div>
                    @error('currentSerial') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @error('scannedSerials') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if(count($scannedSerials) > 0)
                    <div class="mt-2">
                        <span class="text-xs font-semibold text-gray-600 uppercase">Captured: {{ count($scannedSerials) }}</span>
                        <div class="flex flex-wrap gap-2 mt-1 max-h-32 overflow-y-auto p-1 border rounded bg-white">
                            @foreach($scannedSerials as $index => $serial)
                            <div class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded text-xs">
                                <span>{{ $serial }}</span>
                                <button wire:click="removeSerial({{ $index }})" class="text-red-500 hover:text-red-700">×</button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
    
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    @if($newItem['image'])
                        <img src="{{ $newItem['image']->temporaryUrl() }}" class="w-16 h-16 object-cover mb-2 rounded-md">
                    @endif
                    <input type="file" wire:model="newItem.image" 
                        class="w-full p-2 border rounded-md file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('newItem.image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-2">
                <button wire:click="$set('isModalOpen', false)" 
                    class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md">Cancel</button>
                <button wire:click="addItem" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Item</button>
            </div>
        </div>
    </div>
    @endif
</div>