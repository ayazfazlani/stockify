<div data-stockify>
    <div class="p-6 flex-1 min-h-screen overflow-y-auto" style="background: #F4F5F8;">
        <!-- Alerts -->
        @if(session()->has('success'))
            <div class="sf-alert sf-alert-ok">
                <i class='bx bx-check-circle'></i>
                {{ session('success') }}
            </div>
        @elseif(session()->has('error'))
            <div class="sf-alert sf-alert-err">
                <i class='bx bx-error-circle'></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6 max-sm:flex-wrap max-sm:gap-3">
            <h1 style="font-size: 24px; font-weight: 700; color: #0F1117;">Items</h1>
            <div class="flex gap-2 max-sm:w-full max-sm:justify-between">
                @can('create items')
                    @feature('bulk-import')
                    <button
                        wire:click="toggleImportModal"
                        class="sf-btn sf-btn-green"
                        style="background: #12B76A; color: white;"
                    >
                        <i class='bx bx-import'></i>
                        <span>Import</span>
                    </button>
                    @endfeature
                    <button
                        wire:click="toggleModal"
                        class="sf-btn sf-btn-blue"
                        style="background: #4361EE; color: white;"
                    >
                        <i class='bx bx-plus'></i>
                        <span>Add Item</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-wrap items-center gap-4 mb-6 max-sm:justify-between">
            <div class="relative w-full md:flex-1">
                <i class='bx bx-search' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search items by name or SKU..."
                    class="sf-input"
                    style="padding-left: 40px;"
                >
            </div>
            <div class="w-full flex justify-end md:flex-1 md:justify-normal">
                <button
                    wire:click="$toggle('inStockOnly')"
                    class="sf-btn {{ $inStockOnly ? 'sf-btn-green' : 'sf-btn-ghost' }}"
                >
                    <i class='bx {{ $inStockOnly ? "bx-check-circle" : "bx-package" }}'></i>
                    In Stock Only
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex gap-6 max-sm:flex-wrap">
            <!-- Items List -->
            <div class="w-full md:w-1/2">
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h2 class="text-lg font-semibold" style="color: #0F1117;">Item List</h2>
                        <span style="font-size: 11px; color: #9CA3B8; font-family: 'JetBrains Mono', monospace;">
                            {{ $items->count() }} items
                        </span>
                    </div>
                    <div style="padding: 12px 14px;">
                        <div class="sf-item-list">
                            @forelse($items as $item)
                                <div
                                    wire:key="item-{{ $item->id }}"
                                    wire:click="selectItem({{ $item->id }})"
                                    class="sf-item {{ $selectedItem && $selectedItem->id === $item->id ? 'selected' : '' }}"
                                >
                                    <div class="sf-chk"></div>
                                    @if($item->image)
                                        <img 
                                            src="{{ asset('storage/' . $item->image) }}" 
                                            alt="{{ $item->name }}"
                                            class="sf-thumb"
                                            style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px;"
                                        >
                                    @else
                                        <div class="sf-thumb-ph">
                                            <i class='bx bx-package'></i>
                                        </div>
                                    @endif
                                    <div class="sf-item-info">
                                        <div class="sf-item-name">{{ $item->name }}</div>
                                        <div class="sf-item-sku">SKU: {{ $item->sku }}</div>
                                        <div class="sf-item-sku" style="margin-top: 2px;">{{ $item->brand }}</div>
                                    </div>
                                    <span class="sf-pill {{ $item->quantity > 0 ? 'ok' : 'low' }}">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                            @empty
                                <div class="sf-empty">
                                    <i class='bx bx-package'></i>
                                    No items found matching your criteria
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Details -->
            <div class="w-full md:w-1/2">
                <div class="sf-card" style="height: 100%;">
                    <div class="sf-card-head">
                        <h2 class="text-lg font-semibold" style="color: #0F1117;">Item Details</h2>
                        <i class='bx bx-info-circle' style="color: #9CA3B8;"></i>
                    </div>
                    <div style="padding: 16px; height: calc(70vh); overflow-y: auto;">
                        @if($selectedItem)
                            <div class="space-y-5">
                                <!-- Images -->
                                @if($selectedItem->images && count($selectedItem->images) > 0)
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($selectedItem->images as $img)
                                            <img 
                                                src="{{ asset('storage/' . $img) }}" 
                                                alt="{{ $selectedItem->name }}"
                                                style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #E8EAF0;"
                                            >
                                        @endforeach
                                    </div>
                                @elseif($selectedItem->image)
                                    <img 
                                        src="{{ asset('storage/' . $selectedItem->image) }}" 
                                        alt="{{ $selectedItem->name }}"
                                        style="width: 100%; height: 160px; object-fit: cover; border-radius: 10px; border: 1px solid #E8EAF0; margin-bottom: 4px;"
                                    >
                                @endif

                                <!-- Details Grid -->
                                <div class="sf-row2">
                                    <div class="sf-field">
                                        <label class="sf-label">Name</label>
                                        <p style="font-weight: 600; color: #0F1117;">{{ $selectedItem->name }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">SKU</label>
                                        <p style="font-family: 'JetBrains Mono', monospace; font-size: 13px;">{{ $selectedItem->sku }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Brand</label>
                                        <p>{{ $selectedItem->brand }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Type</label>
                                        <p>{{ $selectedItem->type }}</p>
                                    </div>
                                    @can('view item cost')
                                        <div class="sf-field">
                                            <label class="sf-label">Cost Price</label>
                                            <p style="font-family: 'JetBrains Mono', monospace;">${{ number_format($selectedItem->cost, 2) }}</p>
                                        </div>
                                    @endcan
                                    <div class="sf-field">
                                        <label class="sf-label">Selling Price</label>
                                        <p style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">${{ number_format($selectedItem->price, 2) }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Quantity</label>
                                        <p class="{{ $selectedItem->quantity > 0 ? 'text-green-600' : 'text-red-600' }}" style="font-weight: 700;">
                                            {{ $selectedItem->quantity }}
                                        </p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Reorder Level</label>
                                        <p>{{ $selectedItem->reorder_level ?? 0 }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Reorder Qty</label>
                                        <p>{{ $selectedItem->reorder_quantity ?? 0 }}</p>
                                    </div>
                                    <div class="sf-field">
                                        <label class="sf-label">Supplier</label>
                                        <p>{{ $selectedItem->supplier?->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                @can('edit items')
                                    <button wire:click="openEditModal" class="sf-btn sf-btn-blue" style="width: 100%; justify-content: center;">
                                        <i class='bx bx-edit'></i> Edit Reorder/Supplier
                                    </button>
                                @endcan
                            </div>
                        @else
                            <div class="sf-empty" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                <i class='bx bx-folder-open' style="font-size: 48px;"></i>
                                <p>Select an item to view details</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        @if($isModalOpen)
            <div class="sf-overlay" wire:click.self="toggleModal">
                <div class="sf-modal">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-plus-circle'></i> Add New Item
                        </span>
                        <button type="button" wire:click="toggleModal" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    @php
                        $tenant = tenant();
                        $currentStoreId = Auth::user()->getCurrentStoreId();
                        $itemCount = \App\Models\Item::where('store_id', $currentStoreId)->count();
                        $canAddItems = $tenant ? $tenant->canAdd(\App\Enums\PlanFeature::MAX_ITEMS, $itemCount) : true;
                    @endphp

                    @if(!$canAddItems)
                        <div style="margin: 0 18px; padding: 12px; background: #FFFAEB; border: 1px solid #FDE68A; border-radius: 10px; font-size: 13px; color: #92400E;">
                            <i class='bx bx-info-circle'></i>
                            <strong>Notice:</strong> Your plan allows {{ $tenant->getFeatureLimit(\App\Enums\PlanFeature::MAX_ITEMS) }} items per store. 
                            Please upgrade to add more.
                        </div>
                    @endif

                    <div class="sf-modal-body {{ !$canAddItems ? 'opacity-50 pointer-events-none' : '' }}">
                        <div class="sf-field">
                            <label class="sf-label">SKU / Barcode</label>
                            <input type="text" wire:model="newItem.sku" placeholder="Enter SKU..." class="sf-finput">
                            @error('newItem.sku') <div class="sf-ferr"><i class='bx bx-error-circle'></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="sf-field">
                            <label class="sf-label">Product Name</label>
                            <input type="text" wire:model="newItem.name" placeholder="Product name..." class="sf-finput">
                            @error('newItem.name') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>

                        <div class="sf-row2">
                            <div class="sf-field">
                                <label class="sf-label">Cost Price</label>
                                <input type="number" step="0.01" wire:model="newItem.cost" placeholder="0.00" class="sf-finput">
                                @error('newItem.cost') <div class="sf-ferr">{{ $message }}</div> @enderror
                            </div>
                            <div class="sf-field">
                                <label class="sf-label">Selling Price</label>
                                <input type="number" step="0.01" wire:model="newItem.price" placeholder="0.00" class="sf-finput">
                                @error('newItem.price') <div class="sf-ferr">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="sf-row2">
                            <div class="sf-field">
                                <label class="sf-label">Type</label>
                                <input type="text" wire:model="newItem.type" placeholder="e.g. Electronics" class="sf-finput">
                                @error('newItem.type') <div class="sf-ferr">{{ $message }}</div> @enderror
                            </div>
                            <div class="sf-field">
                                <label class="sf-label">Brand</label>
                                <input type="text" wire:model="newItem.brand" placeholder="e.g. Samsung" class="sf-finput">
                                @error('newItem.brand') <div class="sf-ferr">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="sf-field">
                            <label class="sf-label">Initial Quantity</label>
                            <input type="number" min="0" wire:model="newItem.quantity" placeholder="0" class="sf-finput">
                            @error('newItem.quantity') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>

                        <div class="sf-field">
                            <label class="sf-label">Product Images <span class="opt">(optional, multiple allowed)</span></label>
                            @if($images)
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach($images as $img)
                                        <img src="{{ $img->temporaryUrl() }}" class="sf-img-prev">
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" wire:model="images" multiple class="sf-file">
                            @error('images.*') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="sf-modal-foot">
                        <button type="button" wire:click="toggleModal" class="sf-btn sf-btn-ghost">Cancel</button>
                        <button type="button" wire:click="addItem" class="sf-btn sf-btn-blue">
                            <i class='bx bx-save'></i> Save Item
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit Item Modal -->
        @if($isEditModalOpen)
            <div class="sf-overlay" wire:click.self="$set('isEditModalOpen', false)">
                <div class="sf-modal">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-edit'></i> Edit Item Fields
                        </span>
                        <button type="button" wire:click="$set('isEditModalOpen', false)" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                    <div class="sf-modal-body">
                        <div class="sf-field">
                            <label class="sf-label">Selling Price</label>
                            <input type="number" step="0.01" min="0" wire:model="editForm.price" class="sf-finput">
                            @error('editForm.price') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">Reorder Level</label>
                            <input type="number" min="0" wire:model="editForm.reorder_level" class="sf-finput">
                            @error('editForm.reorder_level') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">Reorder Quantity</label>
                            <input type="number" min="1" wire:model="editForm.reorder_quantity" class="sf-finput">
                            @error('editForm.reorder_quantity') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">Supplier</label>
                            <select wire:model="editForm.supplier_id" class="sf-finput">
                                <option value="">No supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('editForm.supplier_id') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="sf-modal-foot">
                        <button wire:click="$set('isEditModalOpen', false)" class="sf-btn sf-btn-ghost">Cancel</button>
                        <button wire:click="saveItemEdit" class="sf-btn sf-btn-blue">Save Changes</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Import Modal -->
        @feature('bulk-import')
        @if($isImportModalOpen)
            <div class="sf-overlay" wire:click.self="toggleImportModal">
                <div class="sf-modal">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-import'></i> Import Items
                        </span>
                        <button type="button" wire:click="toggleImportModal" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                    <div class="sf-modal-body">
                        <div class="sf-field">
                            <label class="sf-label">Upload CSV/Excel File</label>
                            <input type="file" wire:model.live="importFile" class="sf-file">
                            <div class="sf-hint">Supported formats: .csv, .xlsx, .xls</div>
                            @error('importFile') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="sf-modal-foot">
                        <button wire:click="toggleImportModal" class="sf-btn sf-btn-ghost">Cancel</button>
                        <button wire:click="importItems" class="sf-btn sf-btn-green">Import Items</button>
                    </div>
                </div>
            </div>
        @endif
        @endfeature
    </div>
</div>