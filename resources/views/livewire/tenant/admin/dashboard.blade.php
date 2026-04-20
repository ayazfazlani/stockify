<div>
    <style>
        /* ── Base Layout ───────────────────────────────────── */
        .settings-container {
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }

        .settings-sidebar {
            width: 240px;
            flex-shrink: 0;
        }

        .settings-nav {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 0.5rem 0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }

        .settings-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: hsl(var(--foreground));
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .settings-nav-item:hover {
            background-color: hsl(var(--accent));
        }

        .settings-nav-item.active {
            background-color: hsl(var(--primary) / 0.08);
            color: hsl(var(--primary));
            border-left-color: hsl(var(--primary));
            font-weight: 600;
        }

        .settings-nav-item i {
            width: 1.25rem;
            text-align: center;
        }

        .settings-content {
            flex: 1;
            min-width: 0;
        }

        /* ── Cards ─────────────────────────────────────────── */
        .s-card {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
        }

        .s-card-header {
            margin-bottom: 1.25rem;
        }

        .s-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .s-card-title i {
            color: hsl(var(--primary));
        }

        .s-card-desc {
            color: hsl(var(--muted-foreground));
            font-size: 0.8125rem;
        }

        /* ── Forms ─────────────────────────────────────────── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.375rem;
            color: hsl(var(--foreground));
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid hsl(var(--input));
            border-radius: var(--radius);
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: hsl(var(--ring));
            box-shadow: 0 0 0 2px hsl(var(--ring) / 0.15);
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-hint {
            font-size: 0.6875rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* ── Buttons ───────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid hsl(var(--border));
            color: hsl(var(--foreground));
        }

        .btn-outline:hover {
            background-color: hsl(var(--accent));
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-danger-outline {
            background-color: transparent;
            border: 1px solid hsl(var(--destructive));
            color: hsl(var(--destructive));
        }

        .btn-danger-outline:hover {
            background-color: hsl(var(--destructive) / 0.1);
        }

        /* ── Flash Messages ────────────────────────────────── */
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.8125rem;
            font-weight: 500;
            animation: flashSlide 0.3s ease;
        }

        .flash-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
        }

        /* ── Plan Card ─────────────────────────────────────── */
        .current-plan-card {
            border: 2px solid hsl(var(--primary));
            border-radius: calc(var(--radius) * 1.5);
            padding: 1.75rem;
            background: linear-gradient(135deg, hsl(var(--primary) / 0.03), hsl(var(--primary) / 0.08));
            position: relative;
            overflow: hidden;
        }

        .current-plan-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: hsl(var(--primary) / 0.06);
        }

        .plan-badge-active {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: rgba(16, 185, 129, 0.15);
            color: #059669;
        }

        .plan-name-lg {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0.75rem 0 0.25rem;
        }

        .plan-price-lg {
            font-size: 2rem;
            font-weight: 800;
            color: hsl(var(--primary));
        }

        .plan-price-lg .plan-interval {
            font-size: 0.875rem;
            font-weight: 500;
            color: hsl(var(--muted-foreground));
        }

        .plan-meta {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid hsl(var(--border));
            font-size: 0.8125rem;
            color: hsl(var(--muted-foreground));
        }

        .plan-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* ── Usage Bars ────────────────────────────────────── */
        .usage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .usage-item {
            background-color: hsl(var(--background));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 1rem;
        }

        .usage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .usage-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .usage-label i {
            width: 28px;
            height: 28px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
        }

        .usage-value {
            font-size: 0.75rem;
            font-weight: 700;
            color: hsl(var(--muted-foreground));
        }

        .usage-bar-bg {
            height: 8px;
            background-color: hsl(var(--muted));
            border-radius: 4px;
            overflow: hidden;
        }

        .usage-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* ── Feature Grid ──────────────────────────────────── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        .feature-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 500;
            background-color: hsl(var(--background));
            border: 1px solid hsl(var(--border));
            transition: all 0.2s;
        }

        .feature-chip-enabled {
            border-color: rgba(16, 185, 129, 0.3);
            background-color: rgba(16, 185, 129, 0.05);
        }

        .feature-chip-disabled {
            opacity: 0.5;
        }

        .feature-chip i.check {
            color: #10b981;
            font-size: 0.625rem;
        }

        .feature-chip i.lock {
            color: hsl(var(--muted-foreground));
            font-size: 0.625rem;
        }

        .feature-chip i.feat-icon {
            color: hsl(var(--primary));
            width: 20px;
            text-align: center;
        }

        .feature-chip .quota-badge {
            margin-left: auto;
            font-size: 0.6875rem;
            font-weight: 700;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.1);
            padding: 0.125rem 0.375rem;
            border-radius: var(--radius);
        }

        /* ── Feature Group ─────────────────────────────────── */
        .feature-group-title {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: hsl(var(--muted-foreground));
            margin-bottom: 0.5rem;
            padding-bottom: 0.375rem;
            border-bottom: 1px solid hsl(var(--border));
        }

        .feature-group-section {
            margin-bottom: 1.25rem;
        }

        .feature-group-section:last-child {
            margin-bottom: 0;
        }

        /* ── No Plan State ─────────────────────────────────── */
        .no-plan-state {
            text-align: center;
            padding: 3rem;
            color: hsl(var(--muted-foreground));
        }

        .no-plan-state i {
            font-size: 3rem;
            opacity: 0.3;
            margin-bottom: 1rem;
            display: block;
        }

        .no-plan-state h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: hsl(var(--foreground));
            margin-bottom: 0.5rem;
        }

        .no-plan-state p {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        /* ── Toggle Switch ─────────────────────────────────── */
        .toggle-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid hsl(var(--border));
        }

        .toggle-row:last-child {
            border-bottom: none;
        }

        .toggle-info {
            flex: 1;
        }

        .toggle-label {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .toggle-desc {
            font-size: 0.75rem;
            color: hsl(var(--muted-foreground));
        }

        .toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: hsl(var(--border));
            border-radius: 22px;
            transition: all 0.3s;
        }

        .toggle-slider::before {
            content: "";
            position: absolute;
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: hsl(var(--primary));
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(18px);
        }

        /* ── Danger Zone ───────────────────────────────────── */
        .danger-zone {
            border: 1px solid hsl(var(--destructive) / 0.4);
            border-radius: var(--radius);
            padding: 1.25rem;
            background-color: hsl(var(--destructive) / 0.03);
        }

        .danger-zone-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            color: hsl(var(--destructive));
            font-weight: 700;
        }

        .danger-zone p {
            font-size: 0.8125rem;
            color: hsl(var(--muted-foreground));
            margin-bottom: 1rem;
        }

        /* ── Animations ────────────────────────────────────── */
        @keyframes flashSlide {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Responsive ────────────────────────────────────── */
        @media (max-width: 1024px) {
            .settings-container {
                flex-direction: column;
                padding: 1.5rem;
            }

            .settings-sidebar {
                width: 100%;
            }

            .settings-nav {
                display: flex;
                overflow-x: auto;
                gap: 0;
                position: static;
            }

            .settings-nav-item {
                white-space: nowrap;
                border-left: none;
                border-bottom: 3px solid transparent;
                padding: 0.75rem 1rem;
            }

            .settings-nav-item.active {
                border-bottom-color: hsl(var(--primary));
                border-left-color: transparent;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .settings-container {
                padding: 1rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .usage-grid {
                grid-template-columns: 1fr;
            }

            .plan-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    <div class="settings-container">
        {{-- ═══════════════════════════════════════════════ --}}
        {{-- SETTINGS SIDEBAR NAV --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="settings-sidebar">
            <div class="settings-nav">
                <button wire:click="switchSection('general')"
                    class="settings-nav-item {{ $activeSection === 'general' ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>General</span>
                </button>
                <button wire:click="switchSection('team')"
                    class="settings-nav-item {{ $activeSection === 'team' ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Team</span>
                </button>
                <button wire:click="switchSection('billing')"
                    class="settings-nav-item {{ $activeSection === 'billing' ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Billing & Plans</span>
                </button>
                <button wire:click="switchSection('features')"
                    class="settings-nav-item {{ $activeSection === 'features' ? 'active' : '' }}">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Features</span>
                </button>
                <button wire:click="switchSection('notifications')"
                    class="settings-nav-item {{ $activeSection === 'notifications' ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </button>
                <button wire:click="switchSection('security')"
                    class="settings-nav-item {{ $activeSection === 'security' ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security</span>
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- SETTINGS CONTENT --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="settings-content">

            {{-- ─────────────── GENERAL SETTINGS ─────────────── --}}
            @if($activeSection === 'general')
                @if(session()->has('settings-success'))
                    <div class="flash-msg flash-success">
                        <i class="fas fa-check-circle"></i> {{ session('settings-success') }}
                    </div>
                @endif

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-building"></i> Company Information</div>
                        <div class="s-card-desc">Manage your organization's basic details</div>
                    </div>

                    <form wire:submit.prevent="saveGeneralSettings">
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-input" wire:model="companyName"
                                    placeholder="Your company name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact Email</label>
                                <input type="email" class="form-input" wire:model="contactEmail"
                                    placeholder="admin@company.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-textarea" wire:model="companyDescription" rows="3"
                                placeholder="Brief description of your business..."></textarea>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Timezone</label>
                                <select class="form-select" wire:model="timezone">
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">Eastern Time (ET)</option>
                                    <option value="America/Chicago">Central Time (CT)</option>
                                    <option value="America/Denver">Mountain Time (MT)</option>
                                    <option value="America/Los_Angeles">Pacific Time (PT)</option>
                                    <option value="Europe/London">London (GMT)</option>
                                    <option value="Europe/Paris">Paris (CET)</option>
                                    <option value="Asia/Karachi">Pakistan (PKT)</option>
                                    <option value="Asia/Dubai">Dubai (GST)</option>
                                    <option value="Asia/Tokyo">Tokyo (JST)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date Format</label>
                                <select class="form-select" wire:model="dateFormat">
                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="d/m/Y">DD/MM/YYYY</option>
                                    <option value="d-M-Y">DD-Mon-YYYY</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            @endif

            {{-- ─────────────── TEAM ─────────────────────────── --}}
            @if($activeSection === 'team')
                @livewire('team-management')
            @endif

            {{-- ─────────────── BILLING & PLANS ──────────────── --}}
            @if($activeSection === 'billing')
                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-crown"></i> Current Plan</div>
                        <div class="s-card-desc">Your active subscription and billing details</div>
                    </div>

                    @if($currentPlan)
                        <div class="current-plan-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <span class="plan-badge-active">
                                        <i class="fas fa-check-circle"></i>
                                        {{ $currentSubscription ? ucfirst($currentSubscription->stripe_status) : 'Active' }}
                                    </span>
                                    <div class="plan-name-lg">{{ $currentPlan->name }}</div>
                                    <div class="plan-price-lg">
                                        ${{ number_format($currentPlan->amount / 100, 2) }}
                                        <span class="plan-interval">/{{ $currentPlan->interval }}</span>
                                    </div>
                                </div>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-exchange-alt"></i> Change Plan
                                </button>
                            </div>

                            <div class="plan-meta">
                                <div class="plan-meta-item">
                                    <i class="fas fa-puzzle-piece"></i>
                                    {{ $currentPlan->planFeatures->count() }} features included
                                </div>
                                <div class="plan-meta-item">
                                    <i class="fas fa-calendar"></i>
                                    Billing {{ ucfirst($currentPlan->interval) }}ly
                                </div>
                                @if($currentSubscription && $currentSubscription->trial_ends_at)
                                    <div class="plan-meta-item">
                                        <i class="fas fa-hourglass-half"></i>
                                        Trial ends {{ $currentSubscription->trial_ends_at->diffForHumans() }}
                                    </div>
                                @endif
                                <div class="plan-meta-item">
                                    <i class="fas fa-tag"></i>
                                    {{ strtoupper($currentPlan->currency) }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="no-plan-state">
                            <i class="fas fa-gem"></i>
                            <h3>No Active Plan</h3>
                            <p>Subscribe to a plan to unlock features and grow your business.</p>
                            {{ $tenant = tenant('slug') }}
                            <a href="{{$tenant}}/subscription" class="btn btn-primary">
                                <i class="fas fa-rocket"></i> View Plans
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Usage Stats --}}
                @if($currentPlan)
                    <div class="s-card">
                        <div class="s-card-header">
                            <div class="s-card-title"><i class="fas fa-chart-bar"></i> Usage Overview</div>
                            <div class="s-card-desc">Track your resource usage against plan limits</div>
                        </div>

                        <div class="usage-grid">
                            @foreach($usageStats as $key => $stat)
                                @php
                                    $isUnlimited = $stat['limit'] === -1;
                                    $percentage = $isUnlimited ? 15 : ($stat['limit'] > 0 ? min(100, ($stat['used'] / $stat['limit']) * 100) : 0);
                                    $barColor = $percentage > 85 ? '#ef4444' : ($percentage > 60 ? '#f59e0b' : $stat['color']);
                                @endphp
                                <div class="usage-item">
                                    <div class="usage-header">
                                        <span class="usage-label">
                                            <i class="fas {{ $stat['icon'] }}" style="background-color: {{ $stat['color'] }};"></i>
                                            {{ $stat['label'] }}
                                        </span>
                                        <span class="usage-value">
                                            {{ number_format($stat['used']) }}
                                            /
                                            {{ $isUnlimited ? '∞' : number_format($stat['limit']) }}
                                        </span>
                                    </div>
                                    <div class="usage-bar-bg">
                                        <div class="usage-bar-fill"
                                            style="width: {{ $percentage }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                    @if(!$isUnlimited && $stat['limit'] !== null)
                                        <div class="form-hint" style="margin-top: 0.375rem;">
                                            {{ max(0, $stat['limit'] - $stat['used']) }} remaining
                                        </div>
                                    @elseif($isUnlimited)
                                        <div class="form-hint" style="margin-top: 0.375rem; color: #10b981;">
                                            Unlimited
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Available Plans Comparison --}}
                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-layer-group"></i> Available Plans</div>
                        <div class="s-card-desc">Compare plans to find the best fit for your business</div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                        @foreach($availablePlans as $plan)
                            @php $isCurrentPlan = $currentPlan && $currentPlan->id === $plan->id; @endphp
                            <div style="border: {{ $isCurrentPlan ? '2px solid hsl(var(--primary))' : '1px solid hsl(var(--border))' }};
                                                                        border-radius: var(--radius);
                                                                        padding: 1.25rem;
                                                                        {{ $isCurrentPlan ? 'background-color: hsl(var(--primary) / 0.03);' : '' }}
                                                                        position: relative;">
                                @if($isCurrentPlan)
                                    <div
                                        style="position: absolute; top: -8px; right: 12px;
                                                                                                background-color: hsl(var(--primary)); color: white;
                                                                                                font-size: 0.625rem; font-weight: 700; padding: 2px 8px;
                                                                                                border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Current
                                    </div>
                                @endif
                                <div style="font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem;">{{ $plan->name }}</div>
                                <div
                                    style="font-size: 1.5rem; font-weight: 800; color: hsl(var(--primary)); margin-bottom: 0.75rem;">
                                    ${{ number_format($plan->amount / 100, 2) }}
                                    <span
                                        style="font-size: 0.75rem; font-weight: 500; color: hsl(var(--muted-foreground));">/{{ $plan->interval }}</span>
                                </div>
                                <div style="font-size: 0.75rem; color: hsl(var(--muted-foreground)); margin-bottom: 0.75rem;">
                                    {{ $plan->planFeatures->count() }} features included
                                </div>
                                @if(!$isCurrentPlan)
                                    <button class="btn btn-outline btn-sm" style="width: 100%;">
                                        <i class="fas fa-arrow-up"></i>
                                        {{ $currentPlan && $plan->amount > $currentPlan->amount ? 'Upgrade' : 'Switch' }}
                                    </button>
                                @else
                                    <button class="btn btn-sm"
                                        style="width: 100%; background-color: hsl(var(--muted)); color: hsl(var(--muted-foreground)); cursor: default;">
                                        <i class="fas fa-check"></i> Current Plan
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ─────────────── FEATURES ─────────────────────── --}}
            @if($activeSection === 'features')
                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-puzzle-piece"></i> Your Plan Features</div>
                        <div class="s-card-desc">
                            @if($currentPlan)
                                Features included in your <strong>{{ $currentPlan->name }}</strong> plan
                            @else
                                Subscribe to a plan to unlock features
                            @endif
                        </div>
                    </div>

                    @if(!empty($planFeatures))
                        @foreach($planFeatures as $groupName => $features)
                            <div class="feature-group-section">
                                <div class="feature-group-title">{{ $groupName }}</div>
                                <div class="feature-grid">
                                    @foreach($features as $feature)
                                        <div class="feature-chip feature-chip-enabled">
                                            <i class="fas fa-check-circle check"></i>
                                            <i class="fas {{ $feature['icon'] }} feat-icon"></i>
                                            <span>{{ $feature['label'] }}</span>
                                            @if($feature['type'] === 'quota' && $feature['value'] !== null)
                                                <span class="quota-badge">
                                                    {{ $feature['value'] == -1 ? '∞' : number_format((int) $feature['value']) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-plan-state">
                            <i class="fas fa-lock"></i>
                            <h3>No Features Available</h3>
                            <p>Subscribe to a plan to unlock powerful features.</p>
                        </div>
                    @endif
                </div>

                {{-- All Features Comparison --}}
                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-th-list"></i> All Features</div>
                        <div class="s-card-desc">Full list of available features — locked features require a plan upgrade
                        </div>
                    </div>

                    @php $grouped = \App\Enums\PlanFeature::grouped(); @endphp
                    @foreach($grouped as $groupName => $features)
                        <div class="feature-group-section">
                            <div class="feature-group-title">{{ $groupName }}</div>
                            <div class="feature-grid">
                                @foreach($features as $feature)
                                    @php $hasIt = $this->tenantHasFeature($feature->value); @endphp
                                    <div class="feature-chip {{ $hasIt ? 'feature-chip-enabled' : 'feature-chip-disabled' }}">
                                        @if($hasIt)
                                            <i class="fas fa-check-circle check"></i>
                                        @else
                                            <i class="fas fa-lock lock"></i>
                                        @endif
                                        <i class="fas {{ $feature->icon() }} feat-icon"></i>
                                        <span>{{ $feature->label() }}</span>
                                        @if($hasIt && $feature->type() === 'quota')
                                            @php $limit = tenant()?->getFeatureLimit($feature); @endphp
                                            <span class="quota-badge">
                                                {{ $limit == -1 ? '∞' : number_format($limit ?? 0) }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ─────────────── NOTIFICATIONS ────────────────── --}}
            @if($activeSection === 'notifications')
                @if(session()->has('notification-success'))
                    <div class="flash-msg flash-success">
                        <i class="fas fa-check-circle"></i> {{ session('notification-success') }}
                    </div>
                @endif

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-bell"></i> Notification Preferences</div>
                        <div class="s-card-desc">Configure how and when you receive notifications</div>
                    </div>

                    <form wire:submit.prevent="saveNotificationPrefs">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-label">Security Alerts</div>
                                <div class="toggle-desc">Get notified about suspicious login attempts and security events
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model="notifySecurityAlerts">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-label">Billing & Invoices</div>
                                <div class="toggle-desc">Receive notifications about payments, invoices, and subscription
                                    changes</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model="notifyBilling">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-label">Product Updates</div>
                                <div class="toggle-desc">Stay informed about new features and improvements</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model="notifyProductUpdates">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-label">Low Stock Alerts</div>
                                <div class="toggle-desc">Get notified when inventory items are running low</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model="notifyLowStock">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div style="margin-top: 1.25rem;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- ─────────────── SECURITY ─────────────────────── --}}
            @if($activeSection === 'security')
                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-title"><i class="fas fa-shield-alt"></i> Security Settings</div>
                        <div class="s-card-desc">Manage your account security and access controls</div>
                    </div>

                    <div class="toggle-row">
                        <div class="toggle-info">
                            <div class="toggle-label">Two-Factor Authentication</div>
                            <div class="toggle-desc">Add an extra layer of security to your account</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-top: 1.25rem;">
                        <label class="form-label">Session Timeout</label>
                        <select class="form-select" style="max-width: 200px;">
                            <option>15 minutes</option>
                            <option>30 minutes</option>
                            <option selected>1 hour</option>
                            <option>4 hours</option>
                            <option>24 hours</option>
                        </select>
                        <div class="form-hint">Automatically log out after period of inactivity</div>
                    </div>

                    <button class="btn btn-primary" style="margin-top: 0.5rem;">
                        <i class="fas fa-save"></i> Update Security Settings
                    </button>
                </div>

                <div class="danger-zone">
                    <div class="danger-zone-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Danger Zone</span>
                    </div>
                    <p>Once you delete your account, there is no going back. All your data, stores, and team members will be
                        permanently removed.</p>
                    <button class="btn btn-danger-outline">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>