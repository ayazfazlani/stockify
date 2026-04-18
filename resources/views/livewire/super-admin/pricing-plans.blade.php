<div>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Plans</div>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['total_plans'] }}</div>
            <div class="stat-card-desc">All pricing plans</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Plans</div>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['active_plans'] }}</div>
            <div class="stat-card-desc">Currently available</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Plans</div>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['monthly_plans'] }}</div>
            <div class="stat-card-desc">Billed monthly</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Yearly Plans</div>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['yearly_plans'] }}</div>
            <div class="stat-card-desc">Billed yearly</div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="flash-message flash-success">
            <i class="fas fa-check-circle"></i>
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="flash-message flash-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Plans Table -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="card-title">
                <i class="fas fa-tags" style="margin-right: 0.5rem; color: hsl(var(--primary));"></i>
                Pricing Plans Management
            </div>
            <div>
                <button class="btn btn-primary" wire:click="create">
                    <i class="fas fa-plus" style="margin-right: 0.375rem;"></i> Add New Plan
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Interval</th>
                    <th>Features</th>
                    <th>Stripe ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $plan->name }}</div>
                            <div style="font-size: 0.75rem; color: hsl(var(--muted-foreground));">{{ $plan->slug }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 600; font-size: 1.1rem;">${{ number_format($plan->amount / 100, 2) }}</span>
                            <span style="font-size: 0.75rem; color: hsl(var(--muted-foreground));">{{ strtoupper($plan->currency) }}</span>
                        </td>
                        <td>
                            <span class="interval-badge {{ $plan->interval === 'year' ? 'interval-yearly' : 'interval-monthly' }}">
                                {{ ucfirst($plan->interval) }}ly
                            </span>
                        </td>
                        <td>
                            <button wire:click="viewFeatures({{ $plan->id }})" class="feature-count-badge" title="View features">
                                <i class="fas fa-puzzle-piece"></i>
                                {{ $plan->planFeatures->count() }} features
                            </button>
                        </td>
                        <td>
                            <code class="stripe-id">{{ Str::limit($plan->stripe_price_id, 20) }}</code>
                        </td>
                        <td>
                            <button wire:click="toggleActive({{ $plan->id }})" class="status-badge {{ $plan->active ? 'status-active' : 'status-inactive' }}">
                                <span class="status-dot"></span>
                                {{ $plan->active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <button wire:click="edit({{ $plan->id }})" class="btn btn-outline btn-sm" title="Edit Plan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $plan->id }})" class="btn btn-outline btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this plan?')" title="Delete Plan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: hsl(var(--muted-foreground));">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.5;"></i>
                            No plans created yet. Click "Add New Plan" to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding: 1rem 1.5rem;">
            {{ $plans->links() }}
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- CREATE / EDIT PLAN MODAL                               --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showModal)
    <div class="modal-overlay" wire:click.self="$set('showModal', false)">
        <div class="modal-container modal-xl">
            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">
                        <i class="fas {{ $editingId ? 'fa-edit' : 'fa-plus-circle' }}" style="margin-right: 0.5rem;"></i>
                        {{ $editingId ? 'Edit Plan' : 'Create New Plan' }}
                    </h3>
                    <p class="modal-subtitle">
                        {{ $editingId ? 'Update plan details and feature assignments' : 'Set up a new pricing plan with features' }}
                    </p>
                </div>
                <button class="modal-close" wire:click="$set('showModal', false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <!-- Two Column Layout: Plan Info + Pricing -->
                    <div class="modal-grid-2">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </h4>

                            <div class="form-group">
                                <label class="form-label">Plan Name *</label>
                                <input type="text" class="form-input" wire:model="name" placeholder="e.g., Professional">
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-input" wire:model="slug" placeholder="e.g., professional-monthly">
                                <span class="form-hint">URL-friendly identifier (auto-generated if empty)</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label flex-center">
                                    <input type="checkbox" class="form-checkbox" wire:model="active">
                                    <span>Active Plan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-dollar-sign"></i> Pricing
                            </h4>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Amount ($) *</label>
                                    <input type="number" step="0.01" class="form-input" wire:model="amount" placeholder="29.99">
                                    @error('amount') <span class="form-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Currency</label>
                                    <select class="form-input" wire:model="currency">
                                        <option value="usd">USD ($)</option>
                                        <option value="eur">EUR (€)</option>
                                        <option value="gbp">GBP (£)</option>
                                        <option value="cad">CAD ($)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Billing Interval *</label>
                                <select class="form-input" wire:model="interval">
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                                @error('interval') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Features Description (legacy text for pricing page) -->
                    <div class="form-section" style="margin-top: 1.5rem;">
                        <h4 class="form-section-title">
                            <i class="fas fa-align-left"></i> Features Description
                            <span class="form-hint" style="font-weight: 400; margin-left: 0.5rem;">(shown on public pricing page)</span>
                        </h4>
                        <textarea class="form-input" wire:model="features" rows="3"
                            placeholder="Brief description or HTML list of features for the pricing page..."></textarea>
                    </div>

                    {{-- ═══════════════════════════════════════════════════ --}}
                    {{-- FEATURE ASSIGNMENT SECTION                         --}}
                    {{-- ═══════════════════════════════════════════════════ --}}
                    <div class="features-assignment-section">
                        <div class="features-assignment-header">
                            <h4 class="form-section-title" style="margin-bottom: 0;">
                                <i class="fas fa-puzzle-piece"></i> Feature Assignment
                            </h4>
                            <p class="form-hint" style="margin: 0;">Toggle features on/off and set limits for quota features. These control what tenants can access.</p>
                        </div>

                        @foreach($featureGroups as $groupName => $groupFeatures)
                            <div class="feature-group">
                                <div class="feature-group-header">
                                    <span class="feature-group-title">{{ $groupName }}</span>
                                    <span class="feature-group-count">{{ count($groupFeatures) }} features</span>
                                </div>

                                <div class="feature-group-items">
                                    @foreach($groupFeatures as $feature)
                                        <div class="feature-item {{ ($selectedFeatures[$feature->value] ?? false) ? 'feature-item-active' : '' }}">
                                            <div class="feature-item-left">
                                                <label class="feature-toggle">
                                                    <input type="checkbox"
                                                           wire:model.live="selectedFeatures.{{ $feature->value }}"
                                                           class="feature-checkbox">
                                                    <span class="feature-toggle-slider"></span>
                                                </label>
                                                <div class="feature-info">
                                                    <div class="feature-icon-wrapper" style="color: {{ ($selectedFeatures[$feature->value] ?? false) ? 'hsl(var(--primary))' : 'hsl(var(--muted-foreground))' }};">
                                                        <i class="fas {{ $feature->icon() }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="feature-name">{{ $feature->label() }}</div>
                                                        <div class="feature-desc">{{ $feature->description() }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($feature->type() === 'quota')
                                                <div class="feature-item-right">
                                                    <div class="quota-input-wrapper">
                                                        <input type="number"
                                                               wire:model="featureValues.{{ $feature->value }}"
                                                               class="quota-input"
                                                               placeholder="Limit"
                                                               {{ ($selectedFeatures[$feature->value] ?? false) ? '' : 'disabled' }}>
                                                        <span class="quota-hint">-1 = unlimited</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" wire:click="$set('showModal', false)">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">
                            <i class="fas {{ $editingId ? 'fa-save' : 'fa-plus' }}" style="margin-right: 0.375rem;"></i>
                            {{ $editingId ? 'Update Plan' : 'Create Plan' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.375rem;"></i>
                            {{ $editingId ? 'Updating...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- VIEW FEATURES MODAL                                    --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @if($showFeaturesModal && $viewingPlan)
    <div class="modal-overlay" wire:click.self="closeFeaturesModal">
        <div class="modal-container modal-md">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">
                        <i class="fas fa-puzzle-piece" style="margin-right: 0.5rem;"></i>
                        {{ $viewingPlan->name }} — Features
                    </h3>
                    <p class="modal-subtitle">${{ number_format($viewingPlan->amount / 100, 2) }}/{{ $viewingPlan->interval }}</p>
                </div>
                <button class="modal-close" wire:click="closeFeaturesModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                @php $grouped = $viewingPlan->getFeaturesGrouped(); @endphp

                @if(empty($grouped))
                    <div style="text-align: center; padding: 2rem; color: hsl(var(--muted-foreground));">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.5;"></i>
                        No features assigned to this plan yet.
                    </div>
                @else
                    @foreach($grouped as $groupName => $groupFeatures)
                        <div class="view-feature-group">
                            <div class="view-feature-group-title">{{ $groupName }}</div>
                            @foreach($groupFeatures as $item)
                                <div class="view-feature-item">
                                    <div class="view-feature-left">
                                        <i class="fas {{ $item['feature']->icon() }} view-feature-icon"></i>
                                        <span>{{ $item['feature']->label() }}</span>
                                    </div>
                                    <div class="view-feature-right">
                                        @if($item['feature']->type() === 'quota')
                                            <span class="view-feature-value">
                                                {{ $item['value'] == -1 ? 'Unlimited' : number_format((int) $item['value']) }}
                                            </span>
                                        @else
                                            <span class="view-feature-enabled">
                                                <i class="fas fa-check"></i> Enabled
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline" wire:click="closeFeaturesModal">Close</button>
                <button class="btn btn-primary" wire:click="closeFeaturesModal; edit({{ $viewingPlan->id }})">
                    <i class="fas fa-edit" style="margin-right: 0.375rem;"></i> Edit Features
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SCOPED STYLES                                          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <style>
        /* ── Flash Messages ─────────────────────────────────── */
        .flash-message {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }
        .flash-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
        }
        .flash-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
        }

        /* ── Table Enhancements ─────────────────────────────── */
        .interval-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .interval-monthly {
            background-color: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }
        .interval-yearly {
            background-color: rgba(139, 92, 246, 0.1);
            color: #7c3aed;
        }

        .feature-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.3rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            font-weight: 500;
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.2);
            cursor: pointer;
            transition: all 0.2s;
        }
        .feature-count-badge:hover {
            background-color: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.4);
        }

        .stripe-id {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            background-color: hsl(var(--muted));
            border-radius: 4px;
            color: hsl(var(--muted-foreground));
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .status-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        .status-active .status-dot {
            background-color: #10b981;
        }
        .status-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
        .status-inactive .status-dot {
            background-color: #ef4444;
        }

        .btn-danger {
            color: #dc2626 !important;
        }
        .btn-danger:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        /* ── Modal ──────────────────────────────────────────── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 1rem;
            animation: fadeIn 0.2s ease;
        }
        .modal-container {
            background-color: hsl(var(--card));
            border-radius: calc(var(--radius) * 2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }
        .modal-xl {
            width: 100%;
            max-width: 56rem;
        }
        .modal-md {
            width: 100%;
            max-width: 36rem;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid hsl(var(--border));
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: hsl(var(--foreground));
        }
        .modal-subtitle {
            font-size: 0.8125rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }
        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: hsl(var(--muted-foreground));
            padding: 0.5rem;
            border-radius: var(--radius);
            transition: all 0.2s;
        }
        .modal-close:hover {
            background-color: hsl(var(--muted));
            color: hsl(var(--foreground));
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid hsl(var(--border));
            background-color: hsl(var(--muted));
            border-radius: 0 0 calc(var(--radius) * 2) calc(var(--radius) * 2);
        }
        .modal-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* ── Form Sections ──────────────────────────────────── */
        .form-section {
            background-color: hsl(var(--background));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 1.25rem;
        }
        .form-section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: hsl(var(--foreground));
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-section-title i {
            color: hsl(var(--primary));
        }
        .form-error {
            display: block;
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 0.25rem;
        }
        .form-hint {
            display: block;
            font-size: 0.75rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }
        .form-checkbox {
            width: 1rem;
            height: 1rem;
            border-radius: 4px;
            margin-right: 0.5rem;
            accent-color: hsl(var(--primary));
        }
        .flex-center {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        /* ── Feature Assignment Section ─────────────────────── */
        .features-assignment-section {
            margin-top: 1.5rem;
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            overflow: hidden;
        }
        .features-assignment-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, hsl(var(--primary) / 0.05), hsl(var(--primary) / 0.02));
            border-bottom: 1px solid hsl(var(--border));
        }
        .feature-group {
            border-bottom: 1px solid hsl(var(--border));
        }
        .feature-group:last-child {
            border-bottom: none;
        }
        .feature-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            background-color: hsl(var(--muted));
        }
        .feature-group-title {
            font-size: 0.8125rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--muted-foreground));
        }
        .feature-group-count {
            font-size: 0.75rem;
            color: hsl(var(--muted-foreground));
        }
        .feature-group-items {
            padding: 0.5rem 0;
        }

        /* ── Individual Feature Item ────────────────────────── */
        .feature-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            transition: background-color 0.2s;
            border-left: 3px solid transparent;
        }
        .feature-item:hover {
            background-color: hsl(var(--muted) / 0.5);
        }
        .feature-item-active {
            background-color: hsl(var(--primary) / 0.03);
            border-left-color: hsl(var(--primary));
        }
        .feature-item-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        .feature-item-right {
            flex-shrink: 0;
        }

        /* ── Toggle Switch ──────────────────────────────────── */
        .feature-toggle {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
        }
        .feature-checkbox {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .feature-toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: hsl(var(--border));
            border-radius: 20px;
            transition: all 0.3s;
        }
        .feature-toggle-slider::before {
            content: "";
            position: absolute;
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s;
        }
        .feature-checkbox:checked + .feature-toggle-slider {
            background-color: hsl(var(--primary));
        }
        .feature-checkbox:checked + .feature-toggle-slider::before {
            transform: translateX(16px);
        }

        /* ── Feature Info ───────────────────────────────────── */
        .feature-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .feature-icon-wrapper {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            background-color: hsl(var(--muted));
            font-size: 0.75rem;
            transition: all 0.2s;
        }
        .feature-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: hsl(var(--foreground));
        }
        .feature-desc {
            font-size: 0.6875rem;
            color: hsl(var(--muted-foreground));
            max-width: 280px;
        }

        /* ── Quota Input ────────────────────────────────────── */
        .quota-input-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        .quota-input {
            width: 90px;
            padding: 0.375rem 0.5rem;
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 600;
            text-align: center;
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
            transition: all 0.2s;
        }
        .quota-input:focus {
            outline: none;
            border-color: hsl(var(--primary));
            box-shadow: 0 0 0 2px hsl(var(--primary) / 0.15);
        }
        .quota-input:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .quota-hint {
            font-size: 0.625rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }

        /* ── View Features Modal ────────────────────────────── */
        .view-feature-group {
            margin-bottom: 1.25rem;
        }
        .view-feature-group:last-child {
            margin-bottom: 0;
        }
        .view-feature-group-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--muted-foreground));
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid hsl(var(--border));
        }
        .view-feature-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius);
            transition: background-color 0.2s;
        }
        .view-feature-item:hover {
            background-color: hsl(var(--muted));
        }
        .view-feature-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        .view-feature-icon {
            color: hsl(var(--primary));
            width: 1rem;
            text-align: center;
        }
        .view-feature-right {
            font-size: 0.8125rem;
        }
        .view-feature-value {
            font-weight: 700;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.1);
            padding: 0.2rem 0.5rem;
            border-radius: var(--radius);
        }
        .view-feature-enabled {
            color: #059669;
            font-weight: 600;
        }

        /* ── Animations ─────────────────────────────────────── */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ─────────────────────────────────────── */
        @media (max-width: 768px) {
            .modal-grid-2 {
                grid-template-columns: 1fr;
            }
            .feature-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .feature-item-right {
                padding-left: 3.5rem;
            }
            .feature-desc {
                max-width: 100%;
            }
        }
    </style>
</div>