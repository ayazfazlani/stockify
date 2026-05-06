<div data-stockify>
    <div class="sf-store-container">
        <div class="sf-store-header">
            <h1 class="sf-page-title">
                <i class='bx bx-store-alt mr-2'></i>
                Store Management
            </h1>
            <p class="sf-page-subtitle">Create and manage multiple stores, assign users, and control access</p>
        </div>

        @if (session()->has('status'))
            <div class="sf-alert sf-alert-success mb-5">
                <i class='bx bx-check-circle'></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Create Store Section -->
        <div class="sf-card mb-6">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-plus-circle mr-2'></i>
                    Create New Store
                </h2>
                @php
                    $tenant = tenant();
                    $canCreate = $tenant ? $tenant->canAdd(\App\Enums\PlanFeature::MAX_STORES, $stores->count()) : true;
                @endphp
                @if(!$canCreate)
                    <span class="sf-badge sf-badge-warning">
                        <i class='bx bx-lock-alt'></i> Plan Limit Reached
                    </span>
                @endif
            </div>
            <div class="sf-card-body">
                @if(!$canCreate)
                    <div class="sf-warning-box mb-4">
                        <i class='bx bx-info-circle'></i>
                        <div>
                            <strong>Notice:</strong> Your current plan allows for a maximum of 
                            {{ $tenant->getFeatureLimit(\App\Enums\PlanFeature::MAX_STORES) }} 
                            {{ Str::plural('store', $tenant->getFeatureLimit(\App\Enums\PlanFeature::MAX_STORES)) }}. 
                            <a href="{{ route('tenant.admin', ['tenant' => $tenantSlug, 'section' => 'billing']) }}" class="font-bold underline">Upgrade your plan</a> to create more stores.
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="createStore" class="space-y-4 {{ !$canCreate ? 'sf-disabled-form' : '' }}">
                    <div class="sf-row2">
                        <div class="sf-field">
                            <label class="sf-label">Store Name</label>
                            <input type="text" wire:model="storeName"
                                class="sf-input"
                                placeholder="Enter store name" required>
                            @error('storeName') <div class="sf-ferr">{{ $message }}</div> @enderror
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">Store Logo</label>
                            <input type="file" wire:model="image" accept="image/*" class="sf-file">
                            <div class="sf-hint">PNG, JPG, WEBP up to 2MB.</div>
                            @error('image') <div class="sf-ferr">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="image" class="sf-hint text-blue-600">Uploading...</div>
                        </div>
                    </div>
                    
                    <div class="sf-field">
                        <label class="sf-label">Store Description</label>
                        <textarea wire:model="storeDescription"
                            class="sf-input" rows="3"
                            placeholder="Enter store description"></textarea>
                    </div>
                    
                    <button type="submit" class="sf-btn sf-btn-blue">
                        <i class='bx bx-store'></i> Create Store
                    </button>
                </form>
            </div>
        </div>

        <!-- Add User to Store Section -->
        <div class="sf-card mb-6">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-user-plus mr-2'></i>
                    Add User to Store
                </h2>
            </div>
            <div class="sf-card-body">
                <div class="sf-row2">
                    <div class="sf-field">
                        <label class="sf-label">Select User</label>
                        <select wire:model="selectedUsers" class="sf-input">
                            <option value="">Select a User</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sf-field">
                        <label class="sf-label">Select Store</label>
                        <select wire:model="selectedStore" class="sf-input">
                            <option value="">Select a Store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button wire:click="addUserToStore" class="sf-btn sf-btn-green mt-2">
                    <i class='bx bx-user-check'></i> Add User to Store
                </button>
            </div>
        </div>

        <!-- Change User Role Section -->
        <div class="sf-card mb-6">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-shield mr-2'></i>
                    Change User Role
                </h2>
            </div>
            <div class="sf-card-body">
                <div class="sf-row2">
                    <div class="sf-field">
                        <label class="sf-label">Select User</label>
                        <select wire:model="selectedUser" class="sf-input">
                            <option value="">Select a User</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sf-field">
                        <label class="sf-label">Select Role</label>
                        <select wire:model="selectedRole" class="sf-input">
                            <option value="">Select a Role</option>
                            @foreach($availableRoles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button wire:click="changeUserRole" class="sf-btn sf-btn-warning mt-2">
                    <i class='bx bx-refresh'></i> Change Role
                </button>
            </div>
        </div>

        <!-- Stores List Section -->
        <div class="sf-card">
            <div class="sf-card-head">
                <h2 class="sf-card-title">
                    <i class='bx bx-store mr-2'></i>
                    Existing Stores
                </h2>
                <span class="sf-badge sf-badge-gray">{{ $stores->count() }} stores</span>
            </div>
            <div class="sf-card-body">
                <div class="sf-stores-grid">
                    @foreach($stores as $store)
                        <div class="sf-store-card">
                            <div class="sf-store-card-header">
                                <div class="sf-store-info">
                                    <img src="{{ $store->logo ? Storage::disk('public')->url($store->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($store->name) }}"
                                        alt="{{ $store->name }} logo" class="sf-store-logo">
                                    <div>
                                        <h3 class="sf-store-name">{{ $store->name }}</h3>
                                        <span class="sf-store-id">ID: {{ $store->id }}</span>
                                    </div>
                                </div>
                                @if($store->owner_id === Auth::id() || Auth::user()->hasRole('super admin'))
                                    <button wire:click="confirmDeleteStore({{ $store->id }})"
                                        class="sf-icon-btn sf-icon-btn-danger" title="Delete Store">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                @endif
                            </div>

                            @if($store->description)
                                <p class="sf-store-description">{{ $store->description }}</p>
                            @endif

                            <div class="sf-store-members">
                                <div class="sf-members-header">
                                    <i class='bx bx-group'></i>
                                    <span>Store Members ({{ $store->users->count() }})</span>
                                </div>
                                <div class="sf-members-list">
                                    @foreach($store->users as $user)
                                        <div class="sf-member-item">
                                            <div class="sf-member-info">
                                                <div class="sf-member-avatar">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium">{{ $user->name }}</div>
                                                    <div class="sf-meta-text">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="sf-role-badge {{ $user->getRoleNames()->first() ?? 'none' }}">
                                                    {{ ucfirst($user->getRoleNames()->first() ?? 'No Role') }}
                                                </span>
                                                <button wire:click="removeUserFromStore({{ $user->id }}, {{ $store->id }})"
                                                    class="sf-icon-btn sf-icon-btn-danger sf-icon-sm"
                                                    onclick="return confirm('Are you sure you want to remove this user from the store?')"
                                                    title="Remove user">
                                                    <i class='bx bx-user-minus'></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($stores->isEmpty())
                    <div class="sf-empty">
                        <i class='bx bx-store-alt'></i>
                        <p>No stores created yet</p>
                        <p class="text-sm mt-1">Use the form above to create your first store</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Store Confirmation Modal -->
        @if($showDeleteModal && $deletingStore)
            <div class="sf-overlay" wire:click.self="$set('showDeleteModal', false)">
                <div class="sf-modal sf-modal-danger">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-trash' style="color: #F04438;"></i>
                            Delete Store Permanently?
                        </span>
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <div class="sf-modal-body">
                        <div class="sf-warning-box sf-warning-box-danger mb-4">
                            <i class='bx bx-error-circle'></i>
                            <div>
                                <p class="font-bold mb-1">Warning: This action cannot be undone.</p>
                                <p>All items, orders, and data associated with <strong>{{ $deletingStore->name }}</strong> will be permanently deleted.</p>
                            </div>
                        </div>

                        <div class="sf-field">
                            <label class="sf-label">Please type <strong>{{ $deletingStore->name }}</strong> to confirm deletion:</label>
                            <input type="text" 
                                   wire:model.live="confirmStoreName" 
                                   class="sf-input" 
                                   placeholder="Type store name here...">
                            @error('confirmStoreName')
                                <div class="sf-ferr mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="sf-modal-foot">
                        <button wire:click="$set('showDeleteModal', false)" class="sf-btn sf-btn-ghost">
                            Cancel
                        </button>
                        <button wire:click="delete" 
                                class="sf-btn sf-btn-red"
                                {{ $confirmStoreName !== $deletingStore->name ? 'disabled' : '' }}>
                            <i class='bx bx-trash'></i> Delete Store
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>