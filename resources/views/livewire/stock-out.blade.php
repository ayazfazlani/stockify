<div class="p-6 max-h-screen overflow-auto bg-white text-gray-900">
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
        <h1 class="text-2xl font-semibold">Stock out</h1>
        <div class="flex gap-2">
            <button wire:click="loadItems" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">
                Refresh
            </button>
        </div>
    </div>

    <!-- Scanner -->
    <div class="mb-6">
        <livewire:qr-scanner />
    </div>

    <!-- Search and Date Filter -->
    <div class="flex items-center gap-4 mb-6 max-sm:flex-wrap">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search items by name or SKU..."
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Stock Out Operations -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold">Stock Out Operations</h2>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Item Selection -->
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium">Select Items</h3>
                        </div>
                        <ul class="space-y-2 max-h-96 overflow-auto">
                            @foreach($items as $item)
                                <li wire:key="item-{{ $item->id }}" wire:click="toggleItemSelection({{ $item->id }})"
                                    class="p-3 border rounded-md cursor-pointer hover:bg-gray-50 {{ in_array($item->id, array_column($selectedItems, 'id')) ? 'bg-red-50 border-red-200' : '' }}">
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
                            <h3 class="font-medium mb-3">Selected for Stock Out</h3>
                            <ul class="space-y-2">
                                @foreach($selectedItems as $index => $item)
                                    <li wire:key="selected-{{ $item['id'] }}"
                                        class="flex justify-between items-center p-2 border rounded-md bg-gray-50">
                                        <div class="flex items-center gap-3">
                                            @if($item['image'])
                                                <img src="{{ Storage::url($item['image']) }}" class="w-8 h-8 object-cover rounded-md">
                                            @endif
                                            <span>{{ $item['name'] }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <input type="number" min="1" max="{{ \App\Models\Item::find($item['id'])->quantity }}"
                                                wire:model.debounce.300ms="selectedItems.{{ $index }}.quantity"
                                                class="w-24 px-2 py-1 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                                            @if(isset($item['selected_serials']))
                                                <div class="flex flex-wrap gap-1 mt-1 max-w-48">
                                                    @foreach($item['selected_serials'] as $serial)
                                                        <span class="text-[10px] bg-blue-100 text-blue-700 px-1 rounded">{{ $serial }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-4 flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    Items: {{ count($selectedItems) }} |
                                    Qty: {{ array_sum(array_column($selectedItems, 'quantity')) }}
                                </div>
                                @if(!auth()->user()->hasRole('viewer'))
                                    <button wire:click="handleStockOut" wire:loading.attr="disabled"
                                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-bold shadow-md transition-all active:scale-95">
                                        <span wire:loading.remove>COMPLETE CHECKOUT</span>
                                        <span wire:loading>
                                            PROCESSING...
                                            <i class="fas fa-spinner fa-spin ml-2"></i>
                                        </span>
                                    </button>
                                @endif
                                    </div>
                                </div>
                            @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Transaction History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold">Stock Out History</h2>
            </div>
            <div class="p-4">
                <div class="overflow-auto max-h-[500px]">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Item</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantity</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Unit Price</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr wire:key="history-{{ $transaction->id }}" 
                                    wire:click="viewReceipt({{ $transaction->sale_id }})"
                                    class="border-b hover:bg-blue-50 cursor-pointer transition-colors group">
                                    <td class="px-4 py-3 text-sm flex items-center gap-2">
                                        <i class="fas fa-receipt text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $transaction->item_name }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-600">{{ $transaction->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">${{ number_format($transaction->unit_price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900">${{ number_format($transaction->total_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        No stock-out transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Receipt Modal -->
    @if($showReceipt && $currentSale)
    <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-[9999] print:bg-white print:p-0">
        <div id="receipt-modal-content" class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden print:shadow-none print:max-w-none print:m-0">
            <!-- Receipt Header -->
            <div class="p-6 text-center border-b border-dashed print:border-none">
                <h2 class="text-2xl font-bold uppercase tracking-wider">{{ $currentSale->store->name ?? 'Stockify Receipt' }}</h2>
                <p class="text-sm text-gray-500 mt-1">Transaction ID: #{{ str_pad($currentSale->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-400">{{ $currentSale->created_at->format('M d, Y - H:i') }}</p>
            </div>

            <!-- Receipt Items -->
            <div class="p-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left">
                            <th class="pb-2">Item</th>
                            <th class="pb-2 text-center">Qty</th>
                            <th class="pb-2 text-right">Price</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic">
                        @foreach($currentSale->transactions as $trans)
                        <tr>
                            <td class="py-3">
                                <div>{{ $trans->item_name }}</div>
                                @php
                                    $serials = \App\Models\ItemSerial::where('item_id', $trans->item_id)
                                                ->where('status', 'sold')
                                                ->where('updated_at', '>=', $trans->created_at->subSeconds(15))
                                                ->take($trans->quantity)
                                                ->pluck('serial_number');
                                @endphp
                                @if($serials->count() > 0)
                                    <div class="text-[10px] text-gray-400 leading-tight">
                                        SN: {{ $serials->implode(', ') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 text-center">{{ $trans->quantity }}</td>
                            <td class="py-3 text-right">${{ number_format($trans->unit_price, 2) }}</td>
                            <td class="py-3 text-right font-medium">${{ number_format($trans->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 border-t pt-4 space-y-2">
                    <div class="flex justify-between text-base font-bold">
                        <span>GRAND TOTAL</span>
                        <span>${{ number_format($currentSale->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Receipt Footer -->
            <div class="p-6 bg-gray-50 text-center text-xs text-gray-500 border-t border-dashed print:bg-white">
                <p>Thank you for your purchase!</p>
                <p class="mt-1">Handled by: {{ $currentSale->user->name ?? 'System' }}</p>
            </div>

            <!-- Modal Actions -->
            <div class="p-4 bg-gray-100 flex gap-2 justify-center print:hidden">
                <button onclick="window.print()" class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 font-medium transition">
                    <i class="fas fa-print mr-2"></i> Print Receipt
                </button>
                <button wire:click="$set('showReceipt', false)" class="flex-1 bg-white text-gray-700 border border-gray-300 py-2 rounded-md hover:bg-gray-50 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }
            #receipt-modal-content, #receipt-modal-content * {
                visibility: visible !important;
            }
            #receipt-modal-content {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .print\:hidden {
                display: none !important;
            }
            @page {
                size: portrait;
                margin: 0;
            }
        }
    </style>
    @endif
</div>