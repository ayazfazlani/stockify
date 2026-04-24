<div>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Total Users</div>
                <div class="stat-card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
            <div class="stat-card-desc">{{ number_format($stats['active_tenants'] ?? 0) }} active tenants</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Subscriptions</div>
                <div class="stat-card-icon" style="background-color: #10b981;">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['active_subscriptions'] ?? 0) }}</div>
            <div class="stat-card-desc">Live active/trialing subscriptions</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Revenue</div>
                <div class="stat-card-icon" style="background-color: #f59e0b;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-value">${{ number_format((float) ($stats['monthly_revenue'] ?? 0), 2) }}</div>
            <div class="stat-card-desc">Estimated recurring monthly revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Churn Rate</div>
                <div class="stat-card-icon" style="background-color: #ef4444;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format((float) ($stats['churn_rate'] ?? 0), 1) }}%</div>
            <div class="stat-card-desc">{{ number_format($stats['blocked_tenants'] ?? 0) }} blocked tenants</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="users">Users</button>
        <button class="tab" data-tab="analytics">Analytics</button>
        <button class="tab" data-tab="settings">Settings</button>
    </div>

    <!-- Overview Tab -->
    <div class="tab-content active" id="overview">
        <div class="grid-2">
            <!-- Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Revenue Overview</div>
                    <div>
                        <button class="btn btn-outline btn-sm">Last 7 days</button>
                        <button class="btn btn-primary btn-sm">Last 30 days</button>
                    </div>
                </div>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Users Table -->
            <div class="users-table-container">
                <div class="table-header">
                <div class="table-title">Recent Tenants</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Email</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTenants as $tenant)
                            @php
                                $planClass = match (strtolower((string) ($tenant->subscription_plan ?? 'basic'))) {
                                    'enterprise' => 'plan-enterprise',
                                    'pro' => 'plan-pro',
                                    default => 'plan-basic',
                                };
                            @endphp
                            <tr>
                                <td>{{ $tenant->name }}</td>
                                <td>{{ $tenant->owner?->email ?? 'N/A' }}</td>
                                <td><span class="plan-badge {{ $planClass }}">{{ ucfirst($tenant->subscription_plan ?? 'Free') }}</span></td>
                                <td>
                                    <span class="status-badge {{ $tenant->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $tenant->is_active ? 'Active' : 'Blocked' }}
                                    </span>
                                </td>
                                <td>{{ optional($tenant->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('super-admin.tenants') }}" class="btn btn-outline btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No tenants found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Users Tab -->
    <div class="tab-content" id="users">
        <div class="card">
            <div class="card-header">
                <div class="card-title">User Management</div>
            </div>
            <p>User management features will be displayed here. This could include user search, filtering, bulk actions,
                and detailed user profiles.</p>
        </div>
    </div>

    <!-- Analytics Tab -->
    <div class="tab-content" id="analytics">
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Usage Analytics</div>
                </div>
                <p>Detailed analytics about user engagement, feature usage, and platform performance.</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Revenue Analytics</div>
                </div>
                <p>Breakdown of revenue by plan, region, and other relevant metrics.</p>
            </div>
        </div>
    </div>

    <!-- Settings Tab -->
    <div class="tab-content" id="settings">
        <div class="card">
            <div class="card-header">
                <div class="card-title">General Settings</div>
            </div>
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-input" value="SaaS Admin Inc.">
            </div>
            <div class="form-group">
                <label class="form-label">Default User Role</label>
                <input type="text" class="form-input" value="User">
            </div>
            <div class="form-group">
                <label class="form-label">Email Notifications</label>
                <div>
                    <input type="checkbox" id="email-notifications" checked>
                    <label for="email-notifications">Send email notifications for new users</label>
                </div>
            </div>
            <button class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.revenueChartData = {
            labels: @json($revenueSeries['labels'] ?? []),
            data: @json($revenueSeries['data'] ?? []),
        };
    </script>
@endpush