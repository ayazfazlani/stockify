@php
    $tenantId = tenancy()->initialized ? tenant('slug') : null;
    $routePrefix = tenancy()->initialized ? 'tenant.' : '';
@endphp

<div data-stockify>
    <div class="sf-settings-container">
        {{-- SETTINGS SIDEBAR --}}
        <div class="sf-settings-sidebar">
            <div class="sf-settings-nav">
                <button wire:click="switchSection('general')"
                    class="sf-settings-nav-item {{ $activeSection === 'general' ? 'active' : '' }}">
                    <i class='bx bx-cog'></i>
                    <span>General</span>
                </button>

                <button wire:click="switchSection('team')"
                    class="sf-settings-nav-item {{ $activeSection === 'team' ? 'active' : '' }}">
                    <i class='bx bx-group'></i>
                    <span>Team</span>
                </button>

                @if(auth()->check() && (auth()->user()->isStoreAdmin() || auth()->user()->isSuperAdmin() || tenant('owner_id') === auth()->id()))
                    <button wire:click="switchSection('billing')"
                        class="sf-settings-nav-item {{ $activeSection === 'billing' ? 'active' : '' }}">
                        <i class='bx bx-credit-card'></i>
                        <span>Billing</span>
                    </button>
                @endif

                <button wire:click="switchSection('features')"
                    class="sf-settings-nav-item {{ $activeSection === 'features' ? 'active' : '' }}">
                    <i class='bx bx-puzzle-piece'></i>
                    <span>Features</span>
                </button>

                <button wire:click="switchSection('notifications')"
                    class="sf-settings-nav-item {{ $activeSection === 'notifications' ? 'active' : '' }}">
                    <i class='bx bx-bell'></i>
                    <span>Notifications</span>
                </button>

                <button wire:click="switchSection('security')"
                    class="sf-settings-nav-item {{ $activeSection === 'security' ? 'active' : '' }}">
                    <i class='bx bx-shield-alt'></i>
                    <span>Security</span>
                </button>
            </div>
        </div>

        {{-- SETTINGS CONTENT --}}
        <div class="sf-settings-content">
            {{-- Success/Error Messages --}}
            @if(session()->has('settings-success'))
                <div class="sf-alert sf-alert-success mb-5">
                    <i class='bx bx-check-circle'></i> {{ session('settings-success') }}
                </div>
            @endif
            @if(session()->has('settings-error'))
                <div class="sf-alert sf-alert-error mb-5">
                    <i class='bx bx-error-circle'></i> {{ session('settings-error') }}
                </div>
            @endif

            {{-- GENERAL SETTINGS --}}
            @if($activeSection === 'general')
                <div class="sf-settings-grid">
                    <div class="sf-card">
                        <div class="sf-card-head">
                            <h3 class="sf-card-title">
                                <i class='bx bx-trending-up'></i> Profit & Margin Leaders
                            </h3>
                        </div>
                        <div class="sf-card-body">
                            <div class="overflow-x-auto">
                                <table class="sf-table-mini">
                                    <thead>
                                        <td>
                                        <th>Item</th>
                                        <th>Margin</th>
                                        <th>Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($marginLeaders as $leader)
                                            <tr>
                                                <td>
                                                    <div class="font-medium">{{ $leader['name'] }}</div>
                                                    <div class="sf-meta-text">{{ $leader['sku'] }} · Qty {{ $leader['qty'] }}
                                                    </div>
                                                </td>
                                                <td class="sf-value-positive">{{ number_format($leader['margin_pct'], 1) }}%
                                                </td>
                                                <td class="sf-currency-value">${{ number_format($leader['profit_pool'], 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="sf-empty-cell">No margin data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="sf-card">
                        <div class="sf-card-head">
                            <h3 class="sf-card-title">
                                <i class='bx bx-history'></i> Inventory Audit Trail
                            </h3>
                        </div>
                        <div class="sf-card-body" style="max-height: 300px; overflow-y: auto;">
                            @forelse($recentAudits as $audit)
                                <div class="sf-audit-item">
                                    <div>
                                        <div class="font-medium">{{ $audit->item?->name ?? 'Item' }} ·
                                            {{ strtoupper($audit->action) }}</div>
                                        <div class="sf-meta-text">{{ $audit->user?->name ?? 'System' }} ·
                                            {{ $audit->created_at?->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-mono text-sm">{{ $audit->before_qty }} → {{ $audit->after_qty }}</div>
                                        <div class="sf-meta-text">{{ $audit->reason ?: 'No reason' }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="sf-empty">No audit events yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-building'></i> Company Information
                        </h3>
                    </div>
                    <div class="sf-card-body">
                        <form wire:submit.prevent="saveGeneralSettings" class="space-y-4">
                            <div class="sf-row2">
                                <div class="sf-field">
                                    <label class="sf-label">Company Name</label>
                                    <input type="text" class="sf-input" wire:model="companyName"
                                        placeholder="Your company name">
                                </div>
                                <div class="sf-field">
                                    <label class="sf-label">Contact Email</label>
                                    <input type="email" class="sf-input" wire:model="contactEmail"
                                        placeholder="admin@company.com">
                                </div>
                            </div>

                            <div class="sf-field">
                                <label class="sf-label">Description</label>
                                <textarea class="sf-input" wire:model="companyDescription" rows="3"
                                    placeholder="Brief description of your business..."></textarea>
                            </div>

                            <div class="sf-field">
                                <label class="sf-label">Tenant Avatar</label>
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="{{ $avatar ? $avatar->temporaryUrl() : ($currentAvatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($companyName ?: 'Tenant')) }}"
                                        alt="Avatar" class="sf-avatar-lg">
                                    <span class="sf-hint">Shown in team/admin contexts</span>
                                </div>
                                <input type="file" class="sf-file" wire:model="avatar" accept="image/*">
                                @error('avatar') <div class="sf-ferr mt-1">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="avatar" class="sf-hint mt-1">Uploading avatar...</div>
                            </div>

                            <div class="sf-row2">
                                <div class="sf-field">
                                    <label class="sf-label">Timezone</label>
                                    <select class="sf-input" wire:model="timezone">
                                        <option value="UTC">UTC</option>
                                        <option value="America/New_York">Eastern Time (ET)</option>
                                        <option value="America/Chicago">Central Time (CT)</option>
                                        <option value="America/Denver">Mountain Time (MT)</option>
                                        <option value="America/Los_Angeles">Pacific Time (PT)</option>
                                        <option value="Europe/London">London (GMT)</option>
                                        <option value="Asia/Karachi">Pakistan (PKT)</option>
                                        <option value="Asia/Dubai">Dubai (GST)</option>
                                        <option value="Asia/Tokyo">Tokyo (JST)</option>
                                    </select>
                                </div>
                                <div class="sf-field">
                                    <label class="sf-label">Date Format</label>
                                    <select class="sf-input" wire:model="dateFormat">
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="d-M-Y">DD-Mon-YYYY</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="sf-btn sf-btn-blue">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- TEAM SECTION --}}
            @if($activeSection === 'team')
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-group'></i> Team Management
                        </h3>
                    </div>
                    <div class="sf-card-body">
                        @if($this->tenantHasFeature('custom-roles'))
                            @livewire('team-management')
                        @else
                            <div class="sf-upgrade-prompt">
                                <i class='bx bx-lock-alt'></i>
                                <h4>Advanced Team Management</h4>
                                <p>Upgrade your plan to unlock custom roles and team member restrictions.</p>
                                <button wire:click="switchSection('billing')" class="sf-btn sf-btn-blue">
                                    <i class='bx bx-rocket'></i> View Plans
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- BILLING SECTION --}}
            @if($activeSection === 'billing')
                <div wire:key="billing-section-{{ time() }}">
                    @if($currentPlan)
                        <div class="sf-current-plan">
                            <div class="sf-plan-badge">Active Plan</div>
                            <h2 class="sf-plan-name">{{ $currentPlan->name }}</h2>
                            <div class="sf-plan-price">
                                ${{ number_format($currentPlan->amount / 100, 2) }}
                                <span>/{{ $currentPlan->interval }}</span>
                            </div>
                            <div class="sf-plan-meta">
                                <span><i class='bx bx-puzzle-piece'></i> {{ $currentPlan->planFeatures->count() }}
                                    features</span>
                                <span><i class='bx bx-calendar'></i> Billing {{ ucfirst($currentPlan->interval) }}ly</span>
                            </div>
                            <a href="{{ route('tenant.subscription.show', ['tenant' => $tenantSlug]) }}"
                                class="sf-btn sf-btn-outline">
                                <i class='bx bx-credit-card'></i> Manage Payment
                            </a>
                        </div>
                    @else
                        <div class="sf-upgrade-prompt">
                            <i class='bx bx-gem'></i>
                            <h4>No Active Plan</h4>
                            <p>Subscribe to a plan to unlock features and grow your business.</p>
                            <a href="{{ route('tenant.subscription.show', ['tenant' => $tenantSlug]) }}"
                                class="sf-btn sf-btn-blue">
                                <i class='bx bx-rocket'></i> View Plans
                            </a>
                        </div>
                    @endif

                    {{-- Usage Stats --}}
                    @if($usageStats && count($usageStats) > 0)
                        <div class="sf-card">
                            <div class="sf-card-head">
                                <h3 class="sf-card-title"><i class='bx bx-chart-bar'></i> Usage Overview</h3>
                            </div>
                            <div class="sf-card-body">
                                <div class="sf-usage-grid">
                                    @foreach($usageStats as $stat)
                                        @php
                                            $isUnlimited = $stat['limit'] === -1;
                                            $percentage = $isUnlimited ? 15 : ($stat['limit'] > 0 ? min(100, ($stat['used'] / $stat['limit']) * 100) : 0);
                                        @endphp
                                        <div class="sf-usage-item">
                                            <div class="sf-usage-header">
                                                <span><i class='bx {{ $stat['icon'] }}'></i> {{ $stat['label'] }}</span>
                                                <span>{{ number_format($stat['used']) }} /
                                                    {{ $isUnlimited ? '∞' : number_format($stat['limit']) }}</span>
                                            </div>
                                            <div class="sf-progress-bar">
                                                <div class="sf-progress-fill" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Available Plans --}}
                    @if($availablePlans && count($availablePlans) > 0)
                        <div class="sf-card">
                            <div class="sf-card-head">
                                <h3 class="sf-card-title"><i class='bx bx-layer'></i> Available Plans</h3>
                            </div>
                            <div class="sf-card-body">
                                <div class="sf-plans-grid">
                                    @foreach($availablePlans as $plan)
                                        @php $isCurrent = $currentPlan && $currentPlan->id === $plan->id; @endphp
                                        <div class="sf-plan-card {{ $isCurrent ? 'current' : '' }}">
                                            @if($isCurrent)
                                                <div class="sf-plan-current-badge">Current</div>
                                            @endif
                                            <h4 class="font-bold text-lg">{{ $plan->name }}</h4>
                                            <div class="sf-plan-card-price">
                                                ${{ number_format($plan->amount / 100, 2) }}
                                                <span>/{{ $plan->interval }}</span>
                                            </div>
                                            <div class="sf-plan-card-features">{{ $plan->planFeatures->count() }} features included
                                            </div>
                                            @if(!$isCurrent)
                                                <a href="{{ route('tenant.subscription.checkout', ['tenant' => $tenantSlug, 'plan' => $plan->id]) }}"
                                                    class="sf-btn sf-btn-sm sf-btn-outline w-full">
                                                    {{ $currentPlan && $plan->amount > $currentPlan->amount ? 'Upgrade' : 'Switch' }}
                                                </a>
                                            @else
                                                <button class="sf-btn sf-btn-sm sf-btn-ghost w-full" disabled>Current Plan</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- FEATURES SECTION --}}
            @if($activeSection === 'features')
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title">
                            <i class='bx bx-puzzle-piece'></i> Your Plan Features
                            @if($currentPlan)
                                <span class="sf-plan-name-badge">{{ $currentPlan->name }}</span>
                            @endif
                        </h3>
                    </div>
                    <div class="sf-card-body">
                        @if(!empty($planFeatures))
                            @foreach($planFeatures as $groupName => $features)
                                <div class="sf-feature-group">
                                    <div class="sf-feature-group-title">{{ $groupName }}</div>
                                    <div class="sf-feature-grid">
                                        @foreach($features as $feature)
                                            <div class="sf-feature-chip enabled">
                                                <i class='bx bx-check-circle'></i>
                                                <span>{{ $feature['label'] }}</span>
                                                @if($feature['type'] === 'quota' && $feature['value'] !== null)
                                                    <span
                                                        class="sf-feature-quota">{{ $feature['value'] == -1 ? '∞' : number_format((int) $feature['value']) }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="sf-upgrade-prompt">
                                <i class='bx bx-lock-alt'></i>
                                <h4>No Features Available</h4>
                                <p>Subscribe to a plan to unlock powerful features.</p>
                                <button wire:click="switchSection('billing')" class="sf-btn sf-btn-blue">
                                    <i class='bx bx-rocket'></i> View Plans
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- NOTIFICATIONS SECTION --}}
            @if($activeSection === 'notifications')
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title"><i class='bx bx-bell'></i> Notification Preferences</h3>
                    </div>
                    <div class="sf-card-body">
                        <form wire:submit.prevent="saveNotificationPrefs">
                            <div class="sf-toggle-row">
                                <div>
                                    <div class="font-medium">Security Alerts</div>
                                    <div class="sf-meta-text">Get notified about suspicious login attempts</div>
                                </div>
                                <label class="sf-toggle">
                                    <input type="checkbox" wire:model="notifySecurityAlerts">
                                    <span class="sf-toggle-slider"></span>
                                </label>
                            </div>
                            <div class="sf-toggle-row">
                                <div>
                                    <div class="font-medium">Billing & Invoices</div>
                                    <div class="sf-meta-text">Receive notifications about payments and invoices</div>
                                </div>
                                <label class="sf-toggle">
                                    <input type="checkbox" wire:model="notifyBilling">
                                    <span class="sf-toggle-slider"></span>
                                </label>
                            </div>
                            <div class="sf-toggle-row">
                                <div>
                                    <div class="font-medium">Low Stock Alerts</div>
                                    <div class="sf-meta-text">Get notified when inventory items are running low</div>
                                </div>
                                <label class="sf-toggle">
                                    <input type="checkbox" wire:model="notifyLowStock">
                                    <span class="sf-toggle-slider"></span>
                                </label>
                            </div>
                            <button type="submit" class="sf-btn sf-btn-blue mt-4"><i class='bx bx-save'></i> Save
                                Preferences</button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- SECURITY SECTION --}}
            @if($activeSection === 'security')
                <div class="sf-card">
                    <div class="sf-card-head">
                        <h3 class="sf-card-title"><i class='bx bx-shield-alt'></i> Security Settings</h3>
                    </div>
                    <div class="sf-card-body">
                        <div class="sf-toggle-row">
                            <div>
                                <div class="font-medium">Two-Factor Authentication</div>
                                <div class="sf-meta-text">Add an extra layer of security to your account</div>
                            </div>
                            <label class="sf-toggle">
                                <input type="checkbox" checked>
                                <span class="sf-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="sf-field mt-4">
                            <label class="sf-label">Session Timeout</label>
                            <select class="sf-input" style="max-width: 200px;">
                                <option>15 minutes</option>
                                <option>30 minutes</option>
                                <option selected>1 hour</option>
                                <option>4 hours</option>
                                <option>24 hours</option>
                            </select>
                        </div>

                        <button class="sf-btn sf-btn-blue mt-2"><i class='bx bx-save'></i> Update Settings</button>
                    </div>
                </div>

                <div class="sf-danger-zone">
                    <div class="sf-danger-zone-header">
                        <i class='bx bx-error-circle'></i>
                        <span>Danger Zone</span>
                    </div>
                    <p>Once you delete your account, there is no going back. All your data will be permanently removed.</p>
                    <button class="sf-btn sf-btn-red sf-btn-sm">
                        <i class='bx bx-trash'></i> Delete Account
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- CSS STYLES HERE (same as before) -->