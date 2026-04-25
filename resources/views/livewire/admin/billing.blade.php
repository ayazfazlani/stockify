<style>
    .content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .stat-card-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: hsl(var(--muted-foreground));
    }

    .stat-card-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .stat-card-value {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-card-desc {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .positive {
        color: #10b981;
    }

    .negative {
        color: #ef4444;
    }

    .chart-container {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .chart-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius);
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        gap: 0.5rem;
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

    .toggle-sidebar {
        background: none;
        border: none;
        cursor: pointer;
        color: hsl(var(--muted-foreground));
        padding: 0.5rem;
        border-radius: var(--radius);
    }

    .toggle-sidebar:hover {
        background-color: hsl(var(--accent));
    }

    .theme-toggle {
        background: none;
        border: none;
        cursor: pointer;
        color: hsl(var(--muted-foreground));
        padding: 0.5rem;
        border-radius: var(--radius);
    }

    .theme-toggle:hover {
        background-color: hsl(var(--accent));
    }

    .tabs {
        display: flex;
        border-bottom: 1px solid hsl(var(--border));
        margin-bottom: 1.5rem;
    }

    .tab {
        padding: 0.75rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: hsl(var(--muted-foreground));
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .tab.active {
        color: hsl(var(--primary));
        border-bottom-color: hsl(var(--primary));
    }

    .tab:hover {
        color: hsl(var(--foreground));
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }

    .plan-card {
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .plan-card:hover {
        border-color: hsl(var(--primary));
    }

    .plan-card.selected {
        border-color: hsl(var(--primary));
        background-color: rgba(59, 130, 246, 0.05);
    }

    .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .plan-name {
        font-weight: 600;
        font-size: 1.125rem;
    }

    .plan-price {
        font-weight: 700;
        font-size: 1.5rem;
    }

    .plan-period {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .plan-features {
        list-style: none;
        margin-bottom: 1.5rem;
    }

    .plan-feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .plan-feature i {
        color: #10b981;
    }

    .billing-table {
        width: 100%;
        border-collapse: collapse;
    }

    .billing-table th,
    .billing-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid hsl(var(--border));
    }

    .billing-table th {
        font-weight: 500;
        color: hsl(var(--muted-foreground));
        font-size: 0.875rem;
    }

    .payment-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-paid {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(180, 83, 9);
    }

    .status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(185, 28, 28);
    }

    .payment-method {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        margin-bottom: 1rem;
    }

    .payment-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        background-color: hsl(var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .payment-info {
        flex: 1;
    }

    .payment-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .payment-details {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .payment-default {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        background-color: rgba(59, 130, 246, 0.1);
        color: rgb(37, 99, 235);
    }

    .invoice-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        margin-bottom: 1rem;
    }

    .invoice-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .invoice-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        background-color: hsl(var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .invoice-details {
        display: flex;
        flex-direction: column;
    }

    .invoice-id {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .invoice-date {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .invoice-amount {
        font-weight: 600;
        font-size: 1.125rem;
    }

    .usage-meter {
        margin-bottom: 1.5rem;
    }

    .usage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .usage-label {
        font-weight: 500;
    }

    .usage-value {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .usage-bar {
        height: 8px;
        background-color: hsl(var(--muted));
        border-radius: 4px;
        overflow: hidden;
    }

    .usage-progress {
        height: 100%;
        border-radius: 4px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid hsl(var(--input));
        border-radius: var(--radius);
        background-color: hsl(var(--background));
        color: hsl(var(--foreground));
        font-size: 0.875rem;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: hsl(var(--ring));
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .form-hint {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin-top: 0.5rem;
    }

    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .sidebar-header h2,
        .nav-item span {
            display: none;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-2,
        .grid-3 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .search-bar {
            width: 200px;
        }

        .chart-actions {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .sidebar {
            position: fixed;
            left: -260px;
            height: 100%;
            z-index: 50;
        }

        .sidebar.open {
            left: 0;
        }

        .header {
            padding: 1rem;
        }

        .content {
            padding: 1rem;
        }

        .search-bar {
            display: none;
        }
    }
</style>
<div>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Revenue</div>
                <div class="stat-card-icon" style="background-color: #10b981;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-value">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</div>
            <div class="stat-card-desc">
                <span class="{{ ($stats['monthly_revenue_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    {{ ($stats['monthly_revenue_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['monthly_revenue_change'] ?? 0 }}%
                </span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Subscriptions</div>
                <div class="stat-card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['active_subscriptions'] ?? 0) }}</div>
            <div class="stat-card-desc">
                <span class="{{ ($stats['active_subscriptions_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    {{ ($stats['active_subscriptions_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['active_subscriptions_change'] ?? 0 }}%
                </span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Churn Rate</div>
                <div class="stat-card-icon" style="background-color: #ef4444;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['churn_rate'] ?? 0, 1) }}%</div>
            <div class="stat-card-desc">
                <span class="{{ ($stats['churn_rate_change'] ?? 0) <= 0 ? 'positive' : 'negative' }}">
                    {{ ($stats['churn_rate_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['churn_rate_change'] ?? 0 }}%
                </span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Avg. Revenue Per User</div>
                <div class="stat-card-icon" style="background-color: #f59e0b;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="stat-card-value">${{ number_format($stats['arpu'] ?? 0, 2) }}</div>
            <div class="stat-card-desc">
                <span class="{{ ($stats['arpu_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    {{ ($stats['arpu_change'] ?? 0) >= 0 ? '+' : '' }}${{ number_format($stats['arpu_change'] ?? 0, 2) }}
                </span> from last month
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="plans">Plans & Pricing</button>
        <button class="tab" data-tab="invoices">Invoices</button>
        <button class="tab" data-tab="payment-methods">Payment Methods</button>
        <button class="tab" data-tab="usage">Usage & Limits</button>
    </div>

    <!-- Overview Tab -->
    <div class="tab-content active" id="overview">
        <div class="grid-2">
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Revenue Trends</div>
                    <div class="chart-actions">
                        <button class="btn btn-outline btn-sm">Monthly</button>
                        <button class="btn btn-primary btn-sm">Quarterly</button>
                    </div>
                </div>
                <canvas id="revenueChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Subscription Growth</div>
                    <div class="chart-actions">
                        <button class="btn btn-outline btn-sm">New</button>
                        <button class="btn btn-primary btn-sm">Net</button>
                    </div>
                </div>
                <canvas id="subscriptionChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Transactions</div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">Export</button>
                </div>
            </div>
            <table class="billing-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Tenant</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>{{ optional($payment->paid_at)->format('M d, Y') }}</td>
                            <td>{{ $payment->tenant->name ?? 'N/A' }}</td>
                            <td>{{ $payment->subscription->plan->name ?? 'Subscription' }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="payment-status {{ $payment->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->stripe_invoice_id)
                                    <a href="{{ route('super-admin.invoices.download', $payment->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors duration-200 group-hover:scale-110" title="Download Invoice">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No recent transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Plans & Pricing Tab -->
    <div class="tab-content" id="plans">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Current Plan</div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">Change Plan</button>
                </div>
            </div>
            <div class="plan-card selected">
                <div class="plan-header">
                    <div class="plan-name">Business Plan</div>
                    <div>
                        <div class="plan-price">$99</div>
                        <div class="plan-period">per month</div>
                    </div>
                </div>
                <ul class="plan-features">
                    <li class="plan-feature">
                        <i class="fas fa-check"></i>
                        <span>Up to 10 team members</span>
                    </li>
                    <li class="plan-feature">
                        <i class="fas fa-check"></i>
                        <span>Advanced analytics</span>
                    </li>
                    <li class="plan-feature">
                        <i class="fas fa-check"></i>
                        <span>Priority support</span>
                    </li>
                    <li class="plan-feature">
                        <i class="fas fa-check"></i>
                        <span>Custom integrations</span>
                    </li>
                    <li class="plan-feature">
                        <i class="fas fa-check"></i>
                        <span>API access</span>
                    </li>
                </ul>
                <div class="form-group">
                    <label class="form-label">Billing Cycle</label>
                    <select class="form-select">
                        <option>Monthly</option>
                        <option selected>Yearly (Save 15%)</option>
                    </select>
                </div>
                <button class="btn btn-primary">Update Plan</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Available Subscription Plans</div>
            </div>
            <div class="grid-3">
                @foreach($allPlans as $plan)
                    <div class="plan-card">
                        <div class="plan-header">
                            <div class="plan-name">{{ $plan->name }}</div>
                            <div>
                                <div class="plan-price">${{ number_format($plan->amount/100, 2) }}</div>
                                <div class="plan-period">per {{ $plan->interval }}</div>
                            </div>
                        </div>
                        <ul class="plan-features">
                            @foreach(explode("\n", $plan->features) as $feature)
                                @if(trim($feature))
                                    <li class="plan-feature">
                                        <i class="fas fa-check"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <button class="btn btn-outline" style="width: 100%;">Edit Plan</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Invoices Tab -->
    <div class="tab-content" id="invoices">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Full Invoice History</div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">Export CSV</button>
                </div>
            </div>
            @forelse($allInvoices as $invoice)
                <div class="invoice-card">
                    <div class="invoice-info">
                        <div class="invoice-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="invoice-details">
                            <div class="invoice-id">{{ $invoice->stripe_invoice_id ?? 'INV-'.$invoice->id }}</div>
                            <div class="invoice-date">{{ optional($invoice->paid_at)->format('F d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $invoice->tenant->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="invoice-amount">${{ number_format($invoice->amount, 2) }}</div>
                    <div class="card-actions">
                        <button class="btn btn-outline btn-sm">View</button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    No invoices found.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Payment Methods Tab -->
    <div class="tab-content" id="payment-methods">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Payment Methods</div>
                <div class="card-actions">
                    <button class="btn btn-primary btn-sm">Add Payment Method</button>
                </div>
            </div>
            <div class="payment-method">
                <div class="payment-icon" style="background-color: #1a1f71;">
                    <i class="fab fa-cc-visa" style="color: white;"></i>
                </div>
                <div class="payment-info">
                    <div class="payment-name">Visa ending in 4242</div>
                    <div class="payment-details">Expires 12/2024</div>
                </div>
                <div class="payment-default">Default</div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">Edit</button>
                </div>
            </div>
            <div class="payment-method">
                <div class="payment-icon" style="background-color: #ff5f00;">
                    <i class="fab fa-cc-mastercard" style="color: white;"></i>
                </div>
                <div class="payment-info">
                    <div class="payment-name">Mastercard ending in 5555</div>
                    <div class="payment-details">Expires 08/2025</div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">Make Default</button>
                    <button class="btn btn-outline btn-sm">Edit</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Billing Information</div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-input" value="SaaS Admin Inc.">
                </div>
                <div class="form-group">
                    <label class="form-label">Billing Email</label>
                    <input type="email" class="form-input" value="billing@saasadmin.com">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <input type="text" class="form-input" value="123 Business Ave">
            </div>
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" class="form-input" value="New York">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" class="form-input" value="NY">
                </div>
                <div class="form-group">
                    <label class="form-label">ZIP Code</label>
                    <input type="text" class="form-input" value="10001">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Country</label>
                <select class="form-select">
                    <option>United States</option>
                    <option>Canada</option>
                    <option>United Kingdom</option>
                </select>
            </div>
            <button class="btn btn-primary">Update Billing Information</button>
        </div>
    </div>

    <!-- Usage & Limits Tab -->
    <div class="tab-content" id="usage">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Current Usage</div>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm">View Detailed Report</button>
                </div>
            </div>
            <div class="usage-meter">
                <div class="usage-header">
                    <div class="usage-label">Total Platform Users</div>
                    <div class="usage-value">{{ $usage['users_count'] }} active accounts</div>
                </div>
                <div class="usage-bar">
                    <div class="usage-progress" style="width: 100%; background-color: #10b981;"></div>
                </div>
            </div>
            <div class="usage-meter">
                <div class="usage-header">
                    <div class="usage-label">Total Tenants</div>
                    <div class="usage-value">{{ $usage['tenants_count'] }} stores created</div>
                </div>
                <div class="usage-bar">
                    <div class="usage-progress" style="width: 100%; background-color: #3b82f6;"></div>
                </div>
            </div>
            <div class="usage-meter">
                <div class="usage-header">
                    <div class="usage-label">Active Subscriptions</div>
                    <div class="usage-value">{{ $usage['active_subs'] }} paying customers</div>
                </div>
                <div class="usage-bar">
                    <div class="usage-progress" style="width: 100%; background-color: #f59e0b;"></div>
                </div>
            </div>
            <div class="usage-meter">
                <div class="usage-header">
                    <div class="usage-label">Projects</div>
                    <div class="usage-value">7 of 15</div>
                </div>
                <div class="usage-bar">
                    <div class="usage-progress" style="width: 47%; background-color: #8b5cf6;"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Billing Cycle</div>
            </div>
            <div class="form-group">
                <label class="form-label">Current Cycle</label>
                <div class="form-hint">June 1, 2023 - June 30, 2023</div>
            </div>
            <div class="form-group">
                <label class="form-label">Next Billing Date</label>
                <div class="form-hint">July 1, 2023</div>
            </div>
            <div class="form-group">
                <label class="form-label">Estimated Next Invoice</label>
                <div class="form-hint">$99.00</div>
            </div>
            <button class="btn btn-outline">Change Billing Cycle</button>
        </div>
    </div>
</div>

<script>
    // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and tab contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Plan selection
        document.querySelectorAll('.plan-card').forEach(card => {
            card.addEventListener('click', function() {
                if (!this.classList.contains('selected')) {
                    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                }
            });
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($charts['labels']),
                datasets: [{
                    label: 'Actual Revenue ($)',
                    data: @json($charts['revenue']),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Subscription Chart
        const subscriptionCtx = document.getElementById('subscriptionChart').getContext('2d');
        const subscriptionChart = new Chart(subscriptionCtx, {
            type: 'bar',
            data: {
                labels: @json($charts['labels']),
                datasets: [{
                    label: 'New Subscriptions',
                    data: @json($charts['new_subs']),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                }, {
                    label: 'Churned',
                    data: @json($charts['churn']),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true
                    }
                }
            }
        });

        
</script>