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
            <div class="stat-card-value">1,248</div>
            <div class="stat-card-desc">+12% from last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Subscriptions</div>
                <div class="stat-card-icon" style="background-color: #10b981;">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
            <div class="stat-card-value">892</div>
            <div class="stat-card-desc">+8% from last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Revenue</div>
                <div class="stat-card-icon" style="background-color: #f59e0b;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-value">$24,580</div>
            <div class="stat-card-desc">+15% from last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Churn Rate</div>
                <div class="stat-card-icon" style="background-color: #ef4444;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card-value">3.2%</div>
            <div class="stat-card-desc">-0.5% from last month</div>
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
                <div class="table-title">Recent Subscribers</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Smith</td>
                        <td>john.smith@example.com</td>
                        <td><span class="plan-badge plan-pro">Pro</span></td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>Apr 15, 2023</td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Sarah Johnson</td>
                        <td>sarah.j@example.com</td>
                        <td><span class="plan-badge plan-enterprise">Enterprise</span></td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>Mar 28, 2023</td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Michael Brown</td>
                        <td>m.brown@example.com</td>
                        <td><span class="plan-badge plan-basic">Basic</span></td>
                        <td><span class="status-badge status-inactive">Inactive</span></td>
                        <td>May 02, 2023</td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Emily Davis</td>
                        <td>emily.davis@example.com</td>
                        <td><span class="plan-badge plan-pro">Pro</span></td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>Apr 22, 2023</td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Robert Wilson</td>
                        <td>robert.w@example.com</td>
                        <td><span class="plan-badge plan-enterprise">Enterprise</span></td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>Mar 15, 2023</td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
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