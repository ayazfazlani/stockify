<div class="p-6 bg-white min-h-screen">
    @if(session()->has('message'))
        <div class="p-3 mb-4 rounded bg-green-100 text-green-800">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Purchase Orders</h1>
        <button wire:click="openCreateModal" class="px-4 py-2 bg-blue-600 text-white rounded">+ Draft PO</button>
    </div>

    <div class="space-y-4">
        @forelse($purchaseOrders as $po)
            <div class="border rounded p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">PO #{{ $po->id }} - {{ $po->supplier?->name ?? 'No Supplier' }}</p>
                        <p class="text-sm text-gray-500">Expected: {{ optional($po->expected_date)->format('M d, Y') }} | Status: {{ ucfirst($po->status) }}</p>
                    </div>
                    <div class="flex gap-2">
                        @if($po->status === 'draft')
                            <button wire:click="markOrdered({{ $po->id }})" class="px-3 py-1 text-sm bg-amber-500 text-white rounded">Mark Ordered</button>
                        @endif
                        <button wire:click="printPurchaseOrder({{ $po->id }})" class="px-3 py-1 text-sm bg-slate-700 text-white rounded">PDF</button>
                        <button wire:click="sharePurchaseOrder({{ $po->id }})" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded">Share Vendor</button>
                    </div>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Item</th>
                            <th class="py-2">Ordered</th>
                            <th class="py-2">Received</th>
                            <th class="py-2">Unit Cost</th>
                            <th class="py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($po->items as $line)
                            <tr class="border-b">
                                <td class="py-2">{{ $line->item?->name }}</td>
                                <td class="py-2">{{ $line->ordered_qty }}</td>
                                <td class="py-2">{{ $line->received_qty }}</td>
                                <td class="py-2">${{ number_format($line->unit_cost, 2) }}</td>
                                <td class="py-2">
                                    @if($line->received_qty < $line->ordered_qty)
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="number"
                                                min="1"
                                                max="{{ $line->ordered_qty - $line->received_qty }}"
                                                wire:model.live="receiveQuantities.{{ $line->id }}"
                                                class="w-20 border p-1 rounded text-xs"
                                                placeholder="Qty"
                                            >
                                            <button wire:click="receiveLine({{ $line->id }})" class="px-2 py-1 bg-green-600 text-white rounded text-xs">Receive</button>
                                        </div>
                                    @else
                                        <span class="text-green-700 text-xs font-semibold">Complete</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="border rounded p-8 text-center text-gray-500">No purchase orders yet.</div>
        @endforelse
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded w-full max-w-3xl p-5 space-y-4">
                <h2 class="text-lg font-semibold">Draft Purchase Order</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm">Supplier</label>
                        <select wire:model="poForm.supplier_id" class="w-full border p-2 rounded">
                            <option value="">Select supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 flex gap-2">
                            <input type="text" wire:model="newSupplierName" class="flex-1 border p-2 rounded text-sm" placeholder="Quick add supplier name">
                            <button wire:click="createSupplier" class="px-2 py-1 text-sm bg-gray-800 text-white rounded">Add</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm">Expected Date</label>
                        <input type="date" wire:model="poForm.expected_date" class="w-full border p-2 rounded">
                    </div>
                </div>

                <div>
                    <label class="text-sm">Notes</label>
                    <textarea wire:model="poForm.notes" class="w-full border p-2 rounded" rows="2"></textarea>
                </div>

                <div class="space-y-2">
                    @foreach($poForm['lines'] as $index => $line)
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <select wire:model="poForm.lines.{{ $index }}.item_id" class="col-span-6 border p-2 rounded">
                                <option value="">Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                                @endforeach
                            </select>
                            <input type="number" min="1" wire:model="poForm.lines.{{ $index }}.ordered_qty" class="col-span-2 border p-2 rounded" placeholder="Qty">
                            <input type="number" step="0.01" min="0" wire:model="poForm.lines.{{ $index }}.unit_cost" class="col-span-3 border p-2 rounded" placeholder="Unit Cost">
                            <button wire:click="removeLine({{ $index }})" class="col-span-1 text-red-600">x</button>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between">
                    <button wire:click="addLine" class="text-sm px-3 py-1 border rounded">+ Add Line</button>
                    <div class="space-x-2">
                        <button wire:click="$set('showCreateModal', false)" class="px-3 py-2 border rounded">Cancel</button>
                        <button wire:click="createPurchaseOrder" class="px-3 py-2 bg-blue-600 text-white rounded">Save Draft</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
