<style>
    .content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .user-profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
    }

    .user-info h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .user-info p {
        color: hsl(var(--muted-foreground));
        margin-bottom: 0.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
    }

    .status-inactive {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(185, 28, 28);
    }

    .plan-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .plan-basic {
        background-color: rgba(59, 130, 246, 0.1);
        color: rgb(37, 99, 235);
    }

    .plan-pro {
        background-color: rgba(168, 85, 247, 0.1);
        color: rgb(126, 34, 206);
    }

    .plan-enterprise {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(180, 83, 9);
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
        justify-content: between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
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

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 500;
    }

    .activity-list {
        list-style: none;
    }

    .activity-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid hsl(var(--border));
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: hsl(var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: hsl(var(--accent-foreground));
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .activity-time {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
    }

    .chart-container {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .chart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .billing-history {
        width: 100%;
        border-collapse: collapse;
    }

    .billing-history th,
    .billing-history td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid hsl(var(--border));
    }

    .billing-history th {
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

    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .sidebar-header h2,
        .nav-item span {
            display: none;
        }

        .grid-2,
        .grid-3 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .search-bar {
            width: 200px;
        }

        .user-profile-header {
            flex-direction: column;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
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
    {{--
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <button class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Users</span>
            </button>
            <h1>User Details</h1>
        </div>
        <div class="header-right">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            <button class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-menu">
                <div class="user-avatar">JD</div>
                <span>John Doe</span>
            </div>
        </div>
    </header> --}}

    <!-- Content -->
    <div class="content">
        <!-- User Profile Header -->
        <div class="user-profile-header">
            <div class="user-avatar-large">JS</div>
            <div class="user-info">
                <h2>John Smith</h2>
                <p>john.smith@example.com</p>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <span class="status-badge status-active">Active</span>
                    <span class="plan-badge plan-pro">Pro Plan</span>
                </div>
            </div>
            <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                <button class="btn btn-outline">
                    <i class="fas fa-envelope"></i>
                    Message
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit User
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" data-tab="overview">Overview</button>
            <button class="tab" data-tab="activity">Activity</button>
            <button class="tab" data-tab="billing">Billing</button>
            <button class="tab" data-tab="settings">Settings</button>
        </div>

        <!-- Overview Tab -->
        <div class="tab-content active" id="overview">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">User Information</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">John Smith</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">john.smith@example.com</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 123-4567</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Location</span>
                            <span class="info-value">New York, USA</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Joined</span>
                            <span class="info-value">April 15, 2023</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Login</span>
                            <span class="info-value">2 hours ago</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Subscription Details</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Plan</span>
                            <span class="info-value">Pro</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge status-active">Active</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Billing Cycle</span>
                            <span class="info-value">Monthly</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Price</span>
                            <span class="info-value">$29.99/month</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Next Billing</span>
                            <span class="info-value">June 15, 2023</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">Visa **** 4242</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">User Activity</div>
                </div>
                <canvas id="userActivityChart"></canvas>
            </div>
        </div>

        <!-- Activity Tab -->
        <div class="tab-content" id="activity">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Activity</div>
                </div>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">User logged in</div>
                            <div class="activity-description">Successful login from New York, USA</div>
                        </div>
                        <div class="activity-time">2 hours ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Report exported</div>
                            <div class="activity-description">Exported monthly sales report as PDF</div>
                        </div>
                        <div class="activity-time">5 hours ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Settings updated</div>
                            <div class="activity-description">Changed notification preferences</div>
                        </div>
                        <div class="activity-time">1 day ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Payment method updated</div>
                            <div class="activity-description">Added new credit card for billing</div>
                        </div>
                        <div class="activity-time">2 days ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Team member added</div>
                            <div class="activity-description">Added Sarah Johnson to the team</div>
                        </div>
                        <div class="activity-time">3 days ago</div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Billing Tab -->
        <div class="tab-content" id="billing">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Billing Information</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Current Plan</span>
                            <span class="info-value">Pro - $29.99/month</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Billing Cycle</span>
                            <span class="info-value">Monthly</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Next Invoice</span>
                            <span class="info-value">June 15, 2023 - $29.99</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">Visa **** 4242</span>
                        </div>
                    </div>
                    <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem;">
                        <button class="btn btn-outline btn-sm">Update Payment Method</button>
                        <button class="btn btn-outline btn-sm">Change Plan</button>
                        <button class="btn btn-outline btn-sm">Cancel Subscription</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Usage Summary</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Storage Used</span>
                            <span class="info-value">4.2 GB / 50 GB</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Team Members</span>
                            <span class="info-value">3 / 10</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">API Calls</span>
                            <span class="info-value">12,458 / 50,000</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Projects</span>
                            <span class="info-value">7 / 15</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Billing History</div>
                </div>
                <table class="billing-history">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>May 15, 2023</td>
                            <td>Pro Plan - Monthly</td>
                            <td>$29.99</td>
                            <td><span class="payment-status status-paid">Paid</span></td>
                            <td><button class="btn btn-outline btn-sm">Download</button></td>
                        </tr>
                        <tr>
                            <td>Apr 15, 2023</td>
                            <td>Pro Plan - Monthly</td>
                            <td>$29.99</td>
                            <td><span class="payment-status status-paid">Paid</span></td>
                            <td><button class="btn btn-outline btn-sm">Download</button></td>
                        </tr>
                        <tr>
                            <td>Mar 15, 2023</td>
                            <td>Pro Plan - Monthly</td>
                            <td>$29.99</td>
                            <td><span class="payment-status status-paid">Paid</span></td>
                            <td><button class="btn btn-outline btn-sm">Download</button></td>
                        </tr>
                        <tr>
                            <td>Feb 15, 2023</td>
                            <td>Basic to Pro Upgrade</td>
                            <td>$15.00</td>
                            <td><span class="payment-status status-paid">Paid</span></td>
                            <td><button class="btn btn-outline btn-sm">Download</button></td>
                        </tr>
                        <tr>
                            <td>Feb 15, 2023</td>
                            <td>Basic Plan - Monthly</td>
                            <td>$14.99</td>
                            <td><span class="payment-status status-paid">Paid</span></td>
                            <td><button class="btn btn-outline btn-sm">Download</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Settings Tab -->
        <div class="tab-content" id="settings">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">User Settings</div>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Email Notifications</span>
                        <span class="info-value">
                            <input type="checkbox" id="email-notifications" checked>
                            <label for="email-notifications">Enabled</label>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Two-Factor Authentication</span>
                        <span class="info-value">
                            <input type="checkbox" id="2fa" checked>
                            <label for="2fa">Enabled</label>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">API Access</span>
                        <span class="info-value">
                            <input type="checkbox" id="api-access" checked>
                            <label for="api-access">Enabled</label>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Data Export</span>
                        <span class="info-value">
                            <input type="checkbox" id="data-export">
                            <label for="data-export">Allow data export</label>
                        </span>
                    </div>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem;">
                    <button class="btn btn-primary">Save Changes</button>
                    <button class="btn btn-outline">Reset to Defaults</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Danger Zone</div>
                </div>
                <div style="display: flex; justify-content: between; align-items: center;">
                    <div>
                        <div style="font-weight: 500; margin-bottom: 0.25rem;">Deactivate Account</div>
                        <div style="font-size: 0.875rem; color: hsl(var(--muted-foreground));">
                            Temporarily disable this user's account
                        </div>
                    </div>
                    <button class="btn btn-outline"
                        style="border-color: hsl(var(--destructive)); color: hsl(var(--destructive));">
                        Deactivate
                    </button>
                </div>
                <div style="height: 1px; background-color: hsl(var(--border)); margin: 1rem 0;"></div>
                <div style="display: flex; justify-content: between; align-items: center;">
                    <div>
                        <div style="font-weight: 500; margin-bottom: 0.25rem;">Delete Account</div>
                        <div style="font-size: 0.875rem; color: hsl(var(--muted-foreground));">
                            Permanently delete this user and all associated data
                        </div>
                    </div>
                    <button class="btn btn-outline"
                        style="border-color: hsl(var(--destructive)); color: hsl(var(--destructive));">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
@push('scripts')
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

        // Back button functionality
        document.querySelector('.back-button').addEventListener('click', function() {
            window.history.back();
        });

        // User Activity Chart
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(userActivityCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Login Count',
                    data: [12, 19, 8, 15, 12, 5, 9],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Actions',
                    data: [5, 12, 6, 10, 8, 2, 7],
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 640) {
            document.querySelector('.toggle-sidebar').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('open');
            });
        }
</script>
@endpush
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}