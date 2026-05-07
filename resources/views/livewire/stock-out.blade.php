<div data-stockify>
    <div class="p-6" style="background: #F4F5F8; min-height: 100vh;">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
                <h1 class="sf-page-title">
                    <i class='bx bx-cart-alt mr-2' style="color: #4361EE;"></i>
                    Stock Out
                </h1>
                <p class="sf-page-subtitle mt-1">Process customer sales and manage inventory outbound</p>
            </div>
            <button wire:click="loadItems" class="sf-btn sf-btn-ghost">
                <i class='bx bx-refresh'></i> Refresh
            </button>
        </div>

        <!-- Scanner -->
            <div class="sf-card mb-6">
                <div class="p-4">
                    <div class="sf-scan-label mb-3">
                        <i class='bx bx-scan'></i> Scan barcode to add item
                    </div>
                    <livewire:qr-scanner />
                </div>
            </div>

        <!-- Search and Date Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <i class='bx bx-search' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search items by name or SKU..."
                    class="sf-input" style="padding-left: 40px;">
            </div>
            <div class="sf-daterange">
                <i class='bx bx-calendar'></i>
                <input type="date" wire:model.live="dateRange.start">
                <span>—</span>
                <input type="date" wire:model.live="dateRange.end">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Stock Out Operations -->
            <div class="space-y-6">
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h2 class="sf-card-title">
                            <i class='bx bx-package mr-2' style="color: #4361EE;"></i>
                            Stock Out Operations
                        </h2>
                    </div>
                    <div class="p-5 space-y-5">
                        <!-- Item Selection -->
                        <div>
                            <h3 class="sf-section-title mb-3">
                                <i class='bx bx-list-ul'></i> Select Items
                            </h3>
                            <div class="sf-item-list" style="max-height: 320px;">
                                @foreach($items as $item)
                                    <div wire:key="item-{{ $item->id }}" 
                                        wire:click="toggleItemSelection({{ $item->id }})"
                                        class="sf-item {{ in_array($item->id, array_column($selectedItems, 'id')) ? 'selected' : '' }}"
                                        style="border-left-color: {{ in_array($item->id, array_column($selectedItems, 'id')) ? '#F04438' : '#E8EAF0' }};">
                                        <div class="sf-chk"></div>
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" class="sf-thumb">
                                        @else
                                            <div class="sf-thumb-ph"><i class='bx bx-package'></i></div>
                                        @endif
                                        <div class="sf-item-info">
                                            <div class="sf-item-name">{{ $item->name }}</div>
                                            <div class="sf-item-sku">SKU: {{ $item->sku }}</div>
                                        </div>
                                        <span class="sf-pill {{ $item->quantity > 0 ? 'ok' : 'low' }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($items) === 0)
                                <div class="sf-empty">
                                    <i class='bx bx-package'></i>
                                    <p>No items found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Selected Items -->
                        @if(count($selectedItems) > 0)
                            <div class="sf-selected-section">
                                <h3 class="sf-section-title mb-3">
                                    <i class='bx bx-check-double'></i> Selected for Stock Out
                                </h3>
                                
                                <!-- Customer Info -->
                                <div class="sf-customer-info mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="sf-field">
                                            <label class="sf-label">Customer Name</label>
                                            <input type="text" wire:model.defer="customerName" 
                                                placeholder="Customer name (optional)"
                                                class="sf-input">
                                        </div>
                                        <div class="sf-field">
                                            <label class="sf-label">Customer Phone</label>
                                            <input type="text" wire:model.defer="customerPhone" 
                                                placeholder="Customer phone (optional)"
                                                class="sf-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Items List -->
                                <div class="space-y-2">
                                    @foreach($selectedItems as $index => $item)
                                        <div wire:key="selected-{{ $item['id'] }}" class="sf-selected-item">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    @if($item['image'])
                                                        <img src="{{ Storage::url($item['image']) }}" class="w-8 h-8 object-cover rounded-sm">
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                                        <div class="sf-meta-text">Available: {{ \App\Models\Item::find($item['id'])->quantity }}</div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <input type="number" min="1" max="{{ \App\Models\Item::find($item['id'])->quantity }}"
                                                        wire:model.live.debounce.300ms="selectedItems.{{ $index }}.quantity"
                                                        class="sf-qty-input" style="width: 80px;">
                                                    <input type="number" min="0" step="0.01"
                                                        wire:model.live.debounce.300ms="selectedItems.{{ $index }}.sale_price"
                                                        class="sf-price-input" style="width: 100px;" placeholder="Sale price">
                                                </div>
                                            </div>
                                            @if(isset($item['selected_serials']) && count($item['selected_serials']) > 0)
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    @foreach($item['selected_serials'] as $serial)
                                                        <span class="sf-serial-chip">{{ $serial }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Checkout Footer -->
                                <div class="sf-checkout-footer">
                                    <div class="sf-checkout-summary">
                                        <span>Items: <strong>{{ count($selectedItems) }}</strong></span>
                                        <span>Total Qty: <strong>{{ array_sum(array_column($selectedItems, 'quantity')) }}</strong></span>
                                    </div>
                                    @can('manage stock')
                                        <button wire:click="handleStockOut" wire:loading.attr="disabled"
                                            class="sf-btn sf-btn-red" style="min-width: 180px;">
                                            <span wire:loading.remove>
                                                <i class='bx bx-check-circle'></i> COMPLETE CHECKOUT
                                            </span>
                                            <span wire:loading>
                                                <i class='bx bx-loader-alt bx-spin'></i> PROCESSING...
                                            </span>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sales History -->
            <div class="sf-card" style="height: fit-content;">
                <div class="sf-card-head">
                    <h2 class="sf-card-title">
                        <i class='bx bx-history mr-2' style="color: #4361EE;"></i>
                        Customer Sales History
                    </h2>
                    <span class="sf-badge sf-badge-gray">{{ $salesHistory->count() }} sales</span>
                </div>
                <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                    <table class="sf-table">
                        <thead>
                            <tr>
                                <th>Date / Time</th>
                                <th>Customer</th>
                                <th>Items</th>
                                @can('view financial metrics')
                                    <th>Total</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesHistory as $sale)
                                <tr wire:key="history-sale-{{ $sale->id }}"
                                    wire:click="viewReceipt({{ $sale->id }})"
                                    class="sf-table-row sf-clickable-row">
                                    <td>
                                        <div class="font-medium">{{ $sale->created_at->format('M d, Y') }}</div>
                                        <div class="sf-meta-text">{{ $sale->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ $sale->customer_name ?: 'Walk-in #' . $sale->id }}</div>
                                        <div class="sf-meta-text">{{ $sale->customer_phone ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="sf-badge sf-badge-light">{{ $sale->transactions_count }} items</span>
                                    </td>
                                    @can('view financial metrics')
                                        <td class="sf-currency-value">${{ number_format($sale->total_amount, 2) }}</td>
                                    @endcan
                                </tr>
                            @empty
                                <tr class="sf-table-empty">
                                    <td colspan="4">
                                        <div class="sf-empty">
                                            <i class='bx bx-receipt'></i>
                                            <p>No customer sales found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        @if($showReceipt && $currentSale)
        <div class="sf-overlay" wire:click.self="$set('showReceipt', false)">
            <div class="sf-modal sf-receipt-modal" id="receipt-modal-content">
                <!-- Receipt Header -->
                <div class="sf-receipt-header">
                    <h2 class="font-bold text-lg tracking-wide">{{ $currentSale->store->name ?? 'Stockify' }}</h2>
                    <p class="text-xs opacity-80 mt-1">Sales Receipt</p>
                    <p class="text-sm mt-2 font-mono">#{{ str_pad($currentSale->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Receipt Body -->
                <div class="sf-receipt-body">
                    <div class="sf-receipt-info">
                        <div><span>Date:</span> {{ $currentSale->created_at->format('M d, Y - h:i A') }}</div>
                        <div><span>Cashier:</span> {{ $currentSale->user->name ?? 'System' }}</div>
                        <div><span>Customer:</span> {{ $currentSale->customer_name ?: 'Walk-in #'.$currentSale->id }}</div>
                        <div><span>Phone:</span> {{ $currentSale->customer_phone ?: '-' }}</div>
                    </div>

                    <table class="sf-receipt-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentSale->transactions as $trans)
                            <tr>
                                <td>
                                    <div>{{ $trans->item_name }}</div>
                                    @php
                                        $serials = \App\Models\ItemSerial::where('item_id', $trans->item_id)
                                                    ->where('status', 'sold')
                                                    ->where('updated_at', '>=', $trans->created_at->subSeconds(15))
                                                    ->take($trans->quantity)
                                                    ->pluck('serial_number');
                                    @endphp
                                    @if($serials->count() > 0)
                                        <div class="sf-serial-chip text-[9px] mt-1">SN: {{ $serials->implode(', ') }}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $trans->quantity }}</td>
                                <td class="text-right">{{ number_format($trans->unit_price, 2) }}</td>
                                <td class="text-right font-medium">{{ number_format($trans->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="sf-receipt-total">
                                <td colspan="3" class="text-right font-bold">GRAND TOTAL</td>
                                <td class="text-right font-bold">{{ number_format($currentSale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Receipt Footer -->
                <div class="sf-receipt-footer">
                    <p>Thank you for your purchase!</p>
                    <p class="mt-1">Handled by: {{ $currentSale->user->name ?? 'System' }}</p>
                </div>

                <!-- Modal Actions -->
                <div class="sf-receipt-actions">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div class="space-y-2">
                            <label class="sf-label">Share by Email</label>
                            <div class="flex gap-2">
                                <input type="email" wire:model.defer="shareEmail" placeholder="customer@email.com" class="sf-input">
                                <button wire:click="shareReceiptByEmail({{ $currentSale->id }})" class="sf-btn sf-btn-blue sf-btn-sm">
                                    Send
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="sf-label">Share by WhatsApp</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model.defer="sharePhone" placeholder="+92xxxxxxxxxx" class="sf-input">
                                <button wire:click="shareReceiptByWhatsApp({{ $currentSale->id }})" class="sf-btn sf-btn-green sf-btn-sm">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="window.print()" class="sf-btn sf-btn-blue flex-1">
                            <i class='bx bx-printer'></i> Print
                        </button>
                        <button wire:click="downloadReceiptPdf({{ $currentSale->id }})" class="sf-btn sf-btn-green flex-1">
                            <i class='bx bx-download'></i> PDF
                        </button>
                        <button wire:click="$set('showReceipt', false)" class="sf-btn sf-btn-ghost flex-1">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>