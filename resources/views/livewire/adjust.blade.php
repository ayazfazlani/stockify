<div data-stockify>
    <div class="p-6 flex-1 min-h-screen overflow-y-auto" style="background: #F4F5F8;">
        <!-- Notifications -->
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

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 style="font-size: 24px; font-weight: 700; color: #0F1117;">Adjust Stock</h1>
            <button wire:click="fetchItems" class="sf-btn sf-btn-ghost">
                <i class='bx bx-refresh'></i> Refresh
            </button>
        </div>

        <!-- Search and Date Filter -->
        <div class="flex items-center gap-4 mb-6 max-sm:flex-wrap">
            <div class="flex-1 relative">
                <i class='bx bx-search' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search items by name or SKU..."
                    class="sf-input" style="padding-left: 40px;">
            </div>
            <div class="w-full flex justify-between md:justify-end md:flex-1 items-center gap-2">
                <div class="sf-daterange">
                    <i class='bx bx-calendar'></i>
                    <input type="date" wire:model.live="dateRange.start">
                    <span>—</span>
                    <input type="date" wire:model.live="dateRange.end">
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-6 sm:flex-nowrap">
            <!-- Left Column: Items List -->
            <div class="w-full sm:flex-1 sf-card" style="height: calc(100vh - 280px); display: flex; flex-direction: column;">
                <div class="sf-card-head">
                    <h2 class="text-lg font-semibold" style="color: #0F1117;">Select Items</h2>
                    @can('create items')
                    <button wire:click="openModal" class="sf-btn sf-btn-green sf-btn-sm">
                        <i class='bx bx-plus'></i> Add Item
                    </button>
                    @endcan
                </div>
                
                <div style="flex: 1; overflow-y: auto; padding: 12px 14px;">
                    @if($loading)
                        <div class="sf-empty">
                            <i class='bx bx-loader-alt bx-spin'></i>
                            <p>Loading items...</p>
                        </div>
                    @else
                        <div class="sf-item-list">
                            @foreach($items as $item)
                                <div wire:key="item-{{ $item->id }}" class="sf-item">
                                    <div class="sf-chk"></div>
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="sf-thumb">
                                    @else
                                        <div class="sf-thumb-ph">
                                            <i class='bx bx-package'></i>
                                        </div>
                                    @endif
                                    <div class="sf-item-info">
                                        <div class="sf-item-name">{{ $item->name }}</div>
                                        <div class="sf-item-sku">SKU: {{ $item->sku }}</div>
                                    </div>
                                    <div class="sf-pill {{ $item->quantity > 0 ? 'ok' : 'low' }}">
                                        {{ $item->quantity }}
                                    </div>
                                    <div class="flex gap-1">
                                        @can('edit items')
                                            <button wire:click="openModal({{ $item->id }})" class="sf-icon-btn" title="Edit">
                                                <i class='bx bx-edit'></i>
                                            </button>
                                        @endcan
                                        @can('delete items')
                                            <button wire:click.prevent="deleteItem({{ $item->id }})" class="sf-icon-btn sf-icon-btn-danger" title="Delete">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Adjust Quantity -->
            <div class="w-full sm:flex-1 sf-card" style="height: calc(100vh - 280px); display: flex; flex-direction: column;">
                <div class="sf-card-head">
                    <h2 class="text-lg font-semibold" style="color: #0F1117;">Adjust Quantity</h2>
                    <i class='bx bx-adjust' style="color: #9CA3B8;"></i>
                </div>
                <div style="flex: 1; overflow-y: auto; padding: 16px;">
                    @if(count($selectedItems) === 0)
                        <div class="sf-empty">
                            <i class='bx bx-folder-open' style="font-size: 48px;"></i>
                            <p>Please select an item to adjust quantity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add/Edit Item Modal -->
        @if($isModalOpen)
        <div class="sf-overlay" wire:click.self="closeModal">
            <div class="sf-modal">
                <div class="sf-modal-head">
                    <span class="sf-modal-title">
                        <i class='bx {{ $isEditing ? "bx-edit" : "bx-plus-circle" }}'></i>
                        {{ $isEditing ? 'Edit Item' : 'Add New Item' }}
                    </span>
                    <button type="button" wire:click="closeModal" class="sf-modal-x">
                        <i class='bx bx-x'></i>
                    </button>
                </div>

                <div class="sf-modal-body">
                    <!-- SKU -->
                    <div class="sf-field">
                        <label class="sf-label">SKU / Barcode</label>
                        <input type="text" wire:model="newItem.sku" placeholder="Enter SKU..." class="sf-finput">
                        @error('newItem.sku') <div class="sf-ferr"><i class='bx bx-error-circle'></i> {{ $message }}</div> @enderror
                    </div>

                    <!-- Name -->
                    <div class="sf-field">
                        <label class="sf-label">Product Name</label>
                        <input type="text" wire:model="newItem.name" placeholder="Product name..." class="sf-finput">
                        @error('newItem.name') <div class="sf-ferr">{{ $message }}</div> @enderror
                    </div>

                    <!-- Cost & Price -->
                    <div class="sf-row2">
                        @can('view item cost')
                        <div class="sf-field">
                            <label class="sf-label">Cost Price</label>
                            <input type="number" step="0.01" wire:model="newItem.cost" placeholder="0.00" class="sf-finput">
                            @error('newItem.cost') <div class="sf-ferr"><i class='bx bx-error-circle'></i> {{ $message }}</div> @enderror
                        </div>
                        @endcan
                        <div class="sf-field">
                            <label class="sf-label">Selling Price</label>
                            <input type="number" step="0.01" wire:model="newItem.price" placeholder="0.00" class="sf-finput">
                            @error('newItem.price') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Type & Brand -->
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

                    <!-- Quantity -->
                    <div class="sf-field">
                        <label class="sf-label">Initial Quantity</label>
                        <input type="number" min="0" wire:model="newItem.quantity" placeholder="0" class="sf-finput">
                        @error('newItem.quantity') <div class="sf-ferr">{{ $message }}</div> @enderror
                    </div>

                    <!-- Reorder Level & Quantity -->
                    <div class="sf-row2">
                        <div class="sf-field">
                            <label class="sf-label">Reorder Level</label>
                            <input type="number" min="0" wire:model="newItem.reorder_level" placeholder="0" class="sf-finput">
                            @error('newItem.reorder_level') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">Reorder Quantity</label>
                            <input type="number" min="1" wire:model="newItem.reorder_quantity" placeholder="1" class="sf-finput">
                            @error('newItem.reorder_quantity') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="sf-field">
                        <label class="sf-label">Supplier</label>
                        <select wire:model="newItem.supplier_id" class="sf-finput">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('newItem.supplier_id') <div class="sf-ferr">{{ $message }}</div> @enderror
                    </div>

                    <!-- Image -->
                    <div class="sf-field">
                        <label class="sf-label">Product Image</label>
                        @if($isEditing && isset($newItem['image']) && !is_object($newItem['image']))
                            <div style="margin-bottom: 8px;">
                                <img src="{{ asset('storage/' . $newItem['image']) }}" alt="Item Image" class="sf-img-prev">
                            </div>
                        @endif
                        <input type="file" wire:model="newItem.image" class="sf-file">
                        @error('newItem.image') <div class="sf-ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="sf-modal-foot">
                    <button type="button" wire:click="closeModal" class="sf-btn sf-btn-ghost">Cancel</button>
                    <button type="button" wire:click="saveItem" class="sf-btn sf-btn-blue">
                        <i class='bx bx-save'></i>
                        {{ $isEditing ? 'Save Changes' : 'Add Item' }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>