<div data-stockify>
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
                <h1 class="sf-page-title">
                    <i class='bx bx-purchase-tag-alt mr-2' style="color: #4361EE;"></i>
                    Purchase Orders
                </h1>
                <p class="sf-page-subtitle mt-1">Manage supplier purchase orders, track deliveries, and receive inventory</p>
            </div>
            <button wire:click="openCreateModal" class="sf-btn sf-btn-blue">
                <i class='bx bx-plus'></i> Draft PO
            </button>
        </div>

        @if(session()->has('message'))
            <div class="sf-alert sf-alert-success mb-6">
                <i class='bx bx-check-circle'></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Purchase Orders List -->
        <div class="space-y-5">
            @forelse($purchaseOrders as $po)
                <div class="sf-po-card">
                    <!-- PO Header -->
                    <div class="sf-po-header">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="sf-po-title">PO #{{ $po->id }}</h3>
                                    <span class="sf-po-status {{ $po->status }}">
                                        <i class='bx {{ $po->status === 'draft' ? 'bx-edit-alt' : ($po->status === 'ordered' ? 'bx-package' : 'bx-check-circle') }}'></i>
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </div>
                                <div class="sf-po-meta mt-2">
                                    <span><i class='bx bx-store'></i> {{ $po->supplier?->name ?? 'No Supplier' }}</span>
                                    <span><i class='bx bx-calendar'></i> Expected: {{ optional($po->expected_date)->format('M d, Y') ?? 'Not set' }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                @if($po->status === 'draft')
                                    <button wire:click="markOrdered({{ $po->id }})" class="sf-btn sf-btn-sm sf-btn-warning">
                                        <i class='bx bx-check'></i> Mark Ordered
                                    </button>
                                @endif
                                <button wire:click="printPurchaseOrder({{ $po->id }})" class="sf-btn sf-btn-sm sf-btn-dark">
                                    <i class='bx bx-printer'></i> PDF
                                </button>
                                <button wire:click="sharePurchaseOrder({{ $po->id }})" class="sf-btn sf-btn-sm sf-btn-purple">
                                    <i class='bx bx-share-alt'></i> Share Vendor
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PO Items Table -->
                    <div class="sf-po-body">
                        <div class="overflow-x-auto">
                            <table class="sf-table sf-table-po">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-right">Ordered</th>
                                        <th class="text-right">Received</th>
                                        <th class="text-right">Unit Cost</th>
                                        <th class="text-right">Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($po->items as $line)
                                        <tr class="sf-table-row">
                                            <td>
                                                <div class="font-medium text-gray-900">{{ $line->item?->name ?? 'Item deleted' }}</div>
                                                <div class="sf-meta-text">SKU: {{ $line->item?->sku ?? 'N/A' }}</div>
                                            </td>
                                            <td class="text-right">
                                                <span class="font-medium">{{ number_format($line->ordered_qty) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="sf-value-neutral">{{ number_format($line->received_qty) }}</span>
                                            </td>
                                            <td class="text-right sf-currency-value">
                                                ${{ number_format($line->unit_cost, 2) }}
                                            </td>
                                            <td class="text-right sf-currency-value">
                                                ${{ number_format($line->ordered_qty * $line->unit_cost, 2) }}
                                            </td>
                                            <td>
                                                @if($line->received_qty < $line->ordered_qty)
                                                    <div class="sf-receive-group">
                                                        <input type="number" 
                                                               min="1" 
                                                               max="{{ $line->ordered_qty - $line->received_qty }}"
                                                               wire:model.live="receiveQuantities.{{ $line->id }}"
                                                               class="sf-input-sm"
                                                               placeholder="Qty">
                                                        <button wire:click="receiveLine({{ $line->id }})" 
                                                                class="sf-btn sf-btn-sm sf-btn-green">
                                                            <i class='bx bx-package'></i> Receive
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="sf-badge sf-badge-success">
                                                        <i class='bx bx-check'></i> Complete
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="sf-table-footer">
                                    <tr>
                                        <td colspan="4" class="text-right font-bold">Total PO Value:</td>
                                        <td class="text-right font-bold sf-currency-value">
                                            ${{ number_format($po->items->sum(fn($line) => $line->ordered_qty * $line->unit_cost), 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sf-empty-card">
                    <div class="sf-empty">
                        <i class='bx bx-purchase-tag-alt' style="font-size: 48px;"></i>
                        <p>No purchase orders yet</p>
                        <p class="text-sm mt-1">Click "Draft PO" to create your first purchase order</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Create Purchase Order Modal -->
        @if($showCreateModal)
            <div class="sf-overlay" wire:click.self="$set('showCreateModal', false)">
                <div class="sf-modal sf-modal-lg">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-plus-circle' style="color: #4361EE;"></i>
                            Draft Purchase Order
                        </span>
                        <button type="button" wire:click="$set('showCreateModal', false)" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <div class="sf-modal-body">
                        <!-- Supplier Section -->
                        <div class="sf-form-section">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="sf-field">
                                    <label class="sf-label">Supplier</label>
                                    <select wire:model="poForm.supplier_id" class="sf-input">
                                        <option value="">Select supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sf-field">
                                    <label class="sf-label">Expected Date</label>
                                    <input type="date" wire:model="poForm.expected_date" class="sf-input">
                                </div>
                            </div>

                            <!-- Quick Add Supplier -->
                            <div class="sf-quick-add">
                                <div class="flex gap-2 flex-1">
                                    <input type="text" wire:model="newSupplierName" class="sf-input flex-1" placeholder="Quick add supplier name">
                                    <button wire:click="createSupplier" class="sf-btn sf-btn-outline">
                                        <i class='bx bx-plus'></i> Add Supplier
                                    </button>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="sf-field mt-4">
                                <label class="sf-label">Notes</label>
                                <textarea wire:model="poForm.notes" class="sf-input" rows="2" placeholder="Additional notes for this purchase order..."></textarea>
                            </div>
                        </div>

                        <!-- PO Lines -->
                        <div class="sf-form-section">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="sf-section-title">
                                    <i class='bx bx-list-ul'></i> Order Lines
                                </h4>
                                <button wire:click="addLine" class="sf-btn sf-btn-sm sf-btn-outline">
                                    <i class='bx bx-plus'></i> Add Line
                                </button>
                            </div>

                            <div class="space-y-2">
                                @foreach($poForm['lines'] as $index => $line)
                                    <div class="sf-po-line">
                                        <div class="grid grid-cols-12 gap-2 items-center">
                                            <select wire:model="poForm.lines.{{ $index }}.item_id" class="col-span-5 sf-input sf-input-sm">
                                                <option value="">Select item</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                                                @endforeach
                                            </select>
                                            <input type="number" min="1" wire:model="poForm.lines.{{ $index }}.ordered_qty" 
                                                   class="col-span-2 sf-input sf-input-sm" placeholder="Qty">
                                            <input type="number" step="0.01" min="0" wire:model="poForm.lines.{{ $index }}.unit_cost" 
                                                   class="col-span-3 sf-input sf-input-sm" placeholder="Unit Cost">
                                            <button wire:click="removeLine({{ $index }})" class="col-span-1 sf-icon-btn sf-icon-btn-danger" title="Remove line">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if(count($poForm['lines']) === 0)
                                <div class="sf-empty-state-small">
                                    <i class='bx bx-package'></i>
                                    <p class="text-sm">No items added yet. Click "Add Line" to add items.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="sf-modal-foot">
                        <button wire:click="$set('showCreateModal', false)" class="sf-btn sf-btn-ghost">
                            Cancel
                        </button>
                        <button wire:click="createPurchaseOrder" class="sf-btn sf-btn-blue">
                            <i class='bx bx-save'></i> Save Draft
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>