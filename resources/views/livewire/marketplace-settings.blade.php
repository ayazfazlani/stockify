@php
    $tenantId = tenancy()->initialized ? tenant('slug') : null;
    $tenant = tenant();
    $hasMarketplaceFeature = $tenant ? \DB::table('plan_features')
        ->where('plan_id', $tenant->plan_id)
        ->where('feature', 'marketplace')
        ->exists() : false;
@endphp

<div data-stockify>
    <div class="sf-marketplace-container">
        <!-- Header -->
        <div class="sf-marketplace-header">
            <div>
                <h1 class="sf-page-title">
                    <i class='bx bx-store-alt mr-2' style="color: #4361EE;"></i>
                    Marketplace Presence
                </h1>
                <p class="sf-page-subtitle mt-1">Enable your store on the public marketplace and manage which items are
                    visible to customers.</p>
            </div>
        </div>

        @if(session()->has('status'))
            <div class="sf-alert sf-alert-success mb-5">
                <i class='bx bx-check-circle'></i>
                {{ session('status') }}
            </div>
        @endif

        @if(!$hasMarketplaceFeature)
            <div class="sf-warning-box mb-5">
                <i class='bx bx-lock-alt'></i>
                <div>
                    <strong>Plan Restriction</strong>
                    <p class="mt-1">Your current plan does not support marketplace visibility.
                        <a href="{{ route('tenant.admin', ['tenant' => $tenantSlug, 'section' => 'billing']) }}"
                            class="font-bold underline">Upgrade to unlock</a>
                    </p>
                </div>
            </div>
        @endif

        <div class="sf-marketplace-grid">
            <!-- Left Column: Store Visibility & Location -->
            <div class="sf-marketplace-left">
                <!-- Store Visibility Card -->
                <div class="sf-card mb-5">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-show-alt mr-2'></i>
                            Store Visibility
                        </h3>
                    </div>
                    <div class="sf-card-body">
                        <div class="sf-visibility-toggle">
                            <div>
                                <div class="font-medium">Public Mode</div>
                                <div class="sf-meta-text">Show store in marketplace search</div>
                            </div>
                            <button wire:click="$toggle('is_public')"
                                class="sf-toggle {{ $is_public ? 'on' : 'off' }} {{ !$hasMarketplaceFeature ? 'disabled' : '' }}"
                                @if(!$hasMarketplaceFeature) disabled @endif>
                                <span class="sf-toggle-slider"></span>
                            </button>
                        </div>

                        @if($is_public)
                            <div class="sf-success-badge mt-4">
                                <i class='bx bx-check-circle'></i>
                                Your store is now LIVE on the marketplace!
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Location Data Card -->
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-map mr-2'></i>
                            Location Data
                        </h3>
                    </div>
                    <div class="sf-card-body">
                        <form wire:submit.prevent="updateStoreSettings" class="space-y-4">
                            <div class="sf-field">
                                <label class="sf-label">Street Address</label>
                                <input type="text" wire:model="address" class="sf-input"
                                    placeholder="Enter street address">
                            </div>

                            <div class="sf-field">
                                <label class="sf-label">City</label>
                                <input type="text" wire:model="city" class="sf-input" placeholder="Enter city">
                            </div>

                            <div class="sf-field">
                                <label class="sf-label">Area / Neighborhood</label>
                                <input type="text" wire:model="area" class="sf-input"
                                    placeholder="e.g., Clifton, Saddar, DHA">
                            </div>

                            <div class="sf-field">
                                <label class="sf-label">Country</label>
                                <select wire:model.live="country" class="sf-input">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sf-row2">
                                <div class="sf-field">
                                    <label class="sf-label">Currency</label>
                                    <select wire:model="currency" class="sf-input">
                                        <option value="">Select Currency</option>
                                        @foreach($currencies as $curr)
                                            <option value="{{ $curr }}">{{ $curr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sf-field">
                                    <label class="sf-label">Currency Symbol</label>
                                    <select wire:model="currency_symbol" class="sf-input">
                                        <option value="">Select Symbol</option>
                                        @foreach($symbols as $sym)
                                            <option value="{{ $sym }}">{{ $sym }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sf-row2">
                                <div class="sf-field">
                                    <label class="sf-label">Latitude</label>
                                    <input type="text" wire:model="latitude" class="sf-input"
                                        placeholder="e.g., 24.8607">
                                </div>
                                <div class="sf-field">
                                    <label class="sf-label">Longitude</label>
                                    <input type="text" wire:model="longitude" class="sf-input"
                                        placeholder="e.g., 67.0011">
                                </div>
                            </div>

                            <button type="button" onclick="detectLocation()" class="sf-btn sf-btn-outline w-full">
                                <i class='bx bx-target-lock'></i> Fetch My Coordinates
                            </button>

                            <button type="submit" class="sf-btn sf-btn-blue w-full">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Item Visibility -->
            <div class="sf-marketplace-right">
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-package mr-2'></i>
                            Manage Inventory Visibility
                        </h3>
                        <span class="sf-badge sf-badge-gray">{{ $items->total() }} items</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="sf-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th class="text-right">Marketplace</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="sf-table-row">
                                        <td>
                                            <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                            <div class="sf-meta-text mt-1">SKU: {{ $item->sku }}</div>
                                        </td>
                                        <td>
                                            <div class="font-semibold sf-currency-value">
                                                {{ $currency_symbol ?? '$' }}{{ number_format($item->price, 2) }}
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <button wire:click="toggleItemPublic({{ $item->id }})"
                                                class="sf-toggle sf-toggle-sm {{ $item->is_public ? 'on' : 'off' }} {{ !$hasMarketplaceFeature ? 'disabled' : '' }}"
                                                @if(!$hasMarketplaceFeature) disabled @endif>
                                                <span class="sf-toggle-slider"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($items->hasPages())
                        <div class="sf-pagination">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>

                <!-- Danger Zone -->
                <div class="sf-danger-zone mt-5">
                    <div class="sf-danger-zone-header">
                        <i class='bx bx-error-circle'></i>
                        <span>Danger Zone</span>
                    </div>
                    <p>Once you delete your store, there is no going back. Please be certain.</p>
                    <button wire:click="confirmDelete" class="sf-btn sf-btn-red sf-btn-sm">
                        <i class='bx bx-trash'></i> Delete Store Permanently
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
            <div class="sf-overlay" wire:click.self="$set('showDeleteModal', false)">
                <div class="sf-modal sf-modal-danger">
                    <div class="sf-modal-head">
                        <span class="sf-modal-title">
                            <i class='bx bx-trash' style="color: #F04438;"></i>
                            Confirm Deletion
                        </span>
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="sf-modal-x">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <div class="sf-modal-body">
                        <div class="sf-warning-box sf-warning-box-danger mb-4">
                            <i class='bx bx-error-circle'></i>
                            <div>
                                <p class="font-bold mb-1">Critical Warning</p>
                                <p>Deleting <strong>{{ $store->name }}</strong> will permanently erase all inventory, order
                                    history, and customer data. This action is irreversible.</p>
                            </div>
                        </div>

                        <div class="sf-field">
                            <label class="sf-label">To proceed, type your store name:</label>
                            <div class="sf-store-name-hint mb-2">{{ $store->name }}</div>
                            <input type="text" wire:model.live="confirmName" class="sf-input"
                                placeholder="Confirm store name">
                            @error('confirmName')
                                <div class="sf-ferr mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="sf-modal-foot">
                        <button wire:click="$set('showDeleteModal', false)" class="sf-btn sf-btn-ghost">
                            Keep Store
                        </button>
                        <button wire:click="delete" class="sf-btn sf-btn-red" {{ $confirmName !== $store->name ? 'disabled' : '' }}>
                            <i class='bx bx-check'></i> Confirm Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <script>
        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    @this.setLocation(position.coords.latitude, position.coords.longitude);
                }, function (error) {
                    alert("Error detecting location: " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>

    <style>
        /* Stockify - Marketplace Settings Styles */
        [data-stockify] .sf-marketplace-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem;
            background: #F4F5F8;
            min-height: 100vh;
        }

        [data-stockify] .sf-marketplace-header {
            margin-bottom: 1.5rem;
        }

        [data-stockify] .sf-page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F1117;
            display: flex;
            align-items: center;
        }

        [data-stockify] .sf-page-subtitle {
            font-size: 0.8125rem;
            color: #9CA3B8;
            margin-top: 0.25rem;
        }

        [data-stockify] .sf-marketplace-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 1.5rem;
        }

        [data-stockify] .sf-marketplace-left {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        [data-stockify] .sf-marketplace-right {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Cards */
        [data-stockify] .sf-card {
            background: white;
            border: 1px solid #E8EAF0;
            border-radius: 4px;
            overflow: hidden;
        }

        [data-stockify] .sf-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #E8EAF0;
            background: #F9FAFB;
        }

        [data-stockify] .sf-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0F1117;
            display: flex;
            align-items: center;
            margin: 0;
        }

        [data-stockify] .sf-card-body {
            padding: 1.25rem;
        }

        /* Toggle Switch */
        [data-stockify] .sf-toggle {
            position: relative;
            display: inline-flex;
            height: 22px;
            width: 42px;
            cursor: pointer;
            border-radius: 20px;
            border: 2px solid transparent;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
        }

        [data-stockify] .sf-toggle.on {
            background-color: #12B76A;
        }

        [data-stockify] .sf-toggle.off {
            background-color: #D1D5E0;
        }

        [data-stockify] .sf-toggle.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        [data-stockify] .sf-toggle-slider {
            display: inline-block;
            height: 18px;
            width: 18px;
            transform: translateX(0);
            background-color: white;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        [data-stockify] .sf-toggle.on .sf-toggle-slider {
            transform: translateX(20px);
        }

        [data-stockify] .sf-toggle-sm {
            height: 18px;
            width: 34px;
        }

        [data-stockify] .sf-toggle-sm .sf-toggle-slider {
            height: 14px;
            width: 14px;
        }

        [data-stockify] .sf-toggle-sm.on .sf-toggle-slider {
            transform: translateX(16px);
        }

        /* Visibility Toggle Row */
        [data-stockify] .sf-visibility-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: #F9FAFB;
            border-radius: 4px;
        }

        /* Success Badge */
        [data-stockify] .sf-success-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #065F46;
        }

        /* Forms */
        [data-stockify] .sf-field {
            margin-bottom: 1rem;
        }

        [data-stockify] .sf-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4B5168;
            margin-bottom: 0.375rem;
        }

        [data-stockify] .sf-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid #E8EAF0;
            border-radius: 2px;
            font-size: 0.8125rem;
            transition: all 0.2s;
        }

        [data-stockify] .sf-input:focus {
            outline: none;
            border-color: #4361EE;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        }

        [data-stockify] .sf-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Buttons */
        [data-stockify] .sf-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        [data-stockify] .sf-btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.6875rem;
        }

        [data-stockify] .sf-btn-blue {
            background: #4361EE;
            color: white;
        }

        [data-stockify] .sf-btn-blue:hover {
            background: #364FC7;
        }

        [data-stockify] .sf-btn-outline {
            background: transparent;
            border: 1px solid #E8EAF0;
            color: #4B5168;
        }

        [data-stockify] .sf-btn-outline:hover {
            background: #F9FAFB;
        }

        [data-stockify] .sf-btn-red {
            background: #F04438;
            color: white;
        }

        [data-stockify] .sf-btn-red:hover {
            background: #D73A2E;
        }

        [data-stockify] .sf-btn-ghost {
            background: #F9FAFB;
            color: #4B5168;
            border: 1px solid #E8EAF0;
        }

        [data-stockify] .sf-btn-ghost:hover {
            background: #E8EAF0;
        }

        /* Table */
        [data-stockify] .sf-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 400px;
        }

        [data-stockify] .sf-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #9CA3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #F9FAFB;
            border-bottom: 1px solid #E8EAF0;
        }

        [data-stockify] .sf-table th.text-right,
        [data-stockify] .sf-table td.text-right {
            text-align: right;
        }

        [data-stockify] .sf-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #E8EAF0;
            font-size: 0.8125rem;
            vertical-align: middle;
        }

        [data-stockify] .sf-table-row:hover td {
            background: #F9FAFB;
        }

        [data-stockify] .sf-currency-value {
            font-weight: 600;
            font-family: 'JetBrains Mono', monospace;
        }

        [data-stockify] .sf-meta-text {
            font-size: 0.6875rem;
            color: #9CA3B8;
            margin-top: 0.25rem;
        }

        /* Badges */
        [data-stockify] .sf-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 2px;
            font-size: 0.6875rem;
            font-weight: 600;
        }

        [data-stockify] .sf-badge-gray {
            background: #F9FAFB;
            color: #4B5168;
            border: 1px solid #E8EAF0;
        }

        /* Alerts */
        [data-stockify] .sf-alert {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 0.8125rem;
        }

        [data-stockify] .sf-alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        /* Warning Box */
        [data-stockify] .sf-warning-box {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            background: #FFFAEB;
            border: 1px solid #FDE68A;
            border-radius: 4px;
            font-size: 0.8125rem;
            color: #92400E;
        }

        [data-stockify] .sf-warning-box-danger {
            background: #FEF3F2;
            border-color: #FECACA;
            color: #991B1B;
        }

        /* Danger Zone */
        [data-stockify] .sf-danger-zone {
            border: 1px solid #FECACA;
            border-radius: 4px;
            padding: 1.25rem;
            background: #FEF3F2;
        }

        [data-stockify] .sf-danger-zone-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #F04438;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* Store Name Hint */
        [data-stockify] .sf-store-name-hint {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8125rem;
            font-weight: 700;
            background: #F9FAFB;
            padding: 0.375rem 0.625rem;
            border-radius: 2px;
            border: 1px solid #E8EAF0;
            display: inline-block;
        }

        /* Pagination */
        [data-stockify] .sf-pagination {
            padding: 0.75rem 1rem;
            border-top: 1px solid #E8EAF0;
            background: #F9FAFB;
        }

        /* Modal */
        [data-stockify] .sf-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 12, 20, 0.54);
            backdrop-filter: blur(3px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1.25rem;
            animation: sf-fade 0.18s ease;
        }

        @keyframes sf-fade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        [data-stockify] .sf-modal {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 16px 40px rgba(15, 17, 23, 0.16);
            animation: sf-up 0.22s ease;
            overflow: hidden;
        }

        [data-stockify] .sf-modal-danger {
            border-top: 3px solid #F04438;
        }

        @keyframes sf-up {
            from {
                transform: translateY(16px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        [data-stockify] .sf-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1.125rem;
            border-bottom: 1px solid #E8EAF0;
        }

        [data-stockify] .sf-modal-body {
            padding: 1.25rem;
        }

        [data-stockify] .sf-modal-foot {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
            padding: 0.75rem 1.125rem;
            border-top: 1px solid #E8EAF0;
            background: #F9FAFB;
        }

        [data-stockify] .sf-modal-title {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        [data-stockify] .sf-modal-x {
            width: 30px;
            height: 30px;
            border-radius: 2px;
            border: 1px solid #E8EAF0;
            background: #F9FAFB;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.125rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            [data-stockify] .sf-marketplace-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            [data-stockify] .sf-marketplace-container {
                padding: 1rem;
            }

            [data-stockify] .sf-row2 {
                grid-template-columns: 1fr;
            }

            [data-stockify] .sf-table th,
            [data-stockify] .sf-table td {
                padding: 0.625rem 0.75rem;
            }
        }

        /* Dark Mode Support */
        body.dark [data-stockify] .sf-marketplace-container {
            background: #1a1a2e;
        }

        body.dark [data-stockify] .sf-card,
        body.dark [data-stockify] .sf-modal {
            background: #1E1E2E;
            border-color: #2D2D3D;
        }

        body.dark [data-stockify] .sf-card-head,
        body.dark [data-stockify] .sf-modal-foot {
            background: #2A2A3A;
            border-bottom-color: #2D2D3D;
        }

        body.dark [data-stockify] .sf-page-title,
        body.dark [data-stockify] .sf-card-title {
            color: #FFFFFF;
        }

        body.dark [data-stockify] .sf-input,
        body.dark [data-stockify] .sf-store-name-hint {
            background: #2A2A3A;
            border-color: #2D2D3D;
            color: #FFFFFF;
        }

        body.dark [data-stockify] .sf-table th {
            background: #2A2A3A;
            border-bottom-color: #2D2D3D;
            color: #6B6B8D;
        }

        body.dark [data-stockify] .sf-table td {
            border-bottom-color: #2D2D3D;
        }

        body.dark [data-stockify] .sf-table-row:hover td {
            background: #2A2A3A;
        }

        body.dark [data-stockify] .sf-badge-gray {
            background: #2A2A3A;
            color: #A1A1B9;
            border-color: #2D2D3D;
        }

        body.dark [data-stockify] .sf-page-subtitle,
        body.dark [data-stockify] .sf-meta-text {
            color: #6B6B8D;
        }

        body.dark [data-stockify] .sf-visibility-toggle {
            background: #2A2A3A;
        }

        body.dark [data-stockify] .sf-btn-outline {
            background: transparent;
            border-color: #2D2D3D;
            color: #A1A1B9;
        }

        body.dark [data-stockify] .sf-btn-outline:hover {
            background: #2A2A3A;
        }

        body.dark [data-stockify] .sf-danger-zone {
            background: rgba(240, 68, 56, 0.1);
            border-color: rgba(240, 68, 56, 0.3);
        }
    </style></div>