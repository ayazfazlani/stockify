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

    .plan-distribution {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .plan-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid hsl(var(--border));
    }

    .plan-item:last-child {
        border-bottom: none;
    }

    .plan-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .plan-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .plan-name {
        font-weight: 500;
    }

    .plan-stats {
        text-align: right;
    }

    .plan-count {
        font-weight: 600;
    }

    .plan-percentage {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .geo-map {
        width: 100%;
        height: 300px;
        background-color: hsl(var(--muted));
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: hsl(var(--muted-foreground));
    }

    .feature-usage {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .feature-name {
        font-weight: 500;
    }

    .feature-bar {
        flex: 1;
        height: 8px;
        background-color: hsl(var(--muted));
        border-radius: 4px;
        margin: 0 1rem;
        overflow: hidden;
    }

    .feature-progress {
        height: 100%;
        border-radius: 4px;
    }

    .feature-value {
        font-weight: 600;
        min-width: 60px;
        text-align: right;
    }

    .conversion-funnel {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .funnel-stage {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .funnel-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: hsl(var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .funnel-content {
        flex: 1;
    }

    .funnel-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .funnel-description {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .funnel-stats {
        text-align: right;
    }

    .funnel-value {
        font-weight: 600;
    }

    .funnel-percentage {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .recent-activities {
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

        .search-bar,
        .date-filter {
            display: none;
        }
    }
</style>

<div>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Monthly Recurring Revenue</div>
                <div class="stat-card-icon" style="background-color: #10b981;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-value">$24,580</div>
            <div class="stat-card-desc">
                <span class="positive">+15.3%</span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Active Users</div>
                <div class="stat-card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">1,248</div>
            <div class="stat-card-desc">
                <span class="positive">+8.7%</span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Churn Rate</div>
                <div class="stat-card-icon" style="background-color: #ef4444;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card-value">3.2%</div>
            <div class="stat-card-desc">
                <span class="positive">-0.5%</span> from last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Avg. Session Duration</div>
                <div class="stat-card-icon" style="background-color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-card-value">4.2m</div>
            <div class="stat-card-desc">
                <span class="positive">+12s</span> from last month
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="revenue">Revenue</button>
        <button class="tab" data-tab="users">Users</button>
        <button class="tab" data-tab="engagement">Engagement</button>
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
                    <div class="chart-title">User Growth</div>
                    <div class="chart-actions">
                        <button class="btn btn-outline btn-sm">New</button>
                        <button class="btn btn-primary btn-sm">Active</button>
                    </div>
                </div>
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <div class="grid-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Plan Distribution</div>
                </div>
                <div class="plan-distribution">
                    <div class="plan-item">
                        <div class="plan-info">
                            <div class="plan-color" style="background-color: #3b82f6;"></div>
                            <div class="plan-name">Basic</div>
                        </div>
                        <div class="plan-stats">
                            <div class="plan-count">412 users</div>
                            <div class="plan-percentage">33%</div>
                        </div>
                    </div>
                    <div class="plan-item">
                        <div class="plan-info">
                            <div class="plan-color" style="background-color: #8b5cf6;"></div>
                            <div class="plan-name">Pro</div>
                        </div>
                        <div class="plan-stats">
                            <div class="plan-count">692 users</div>
                            <div class="plan-percentage">55%</div>
                        </div>
                    </div>
                    <div class="plan-item">
                        <div class="plan-info">
                            <div class="plan-color" style="background-color: #f59e0b;"></div>
                            <div class="plan-name">Enterprise</div>
                        </div>
                        <div class="plan-stats">
                            <div class="plan-count">144 users</div>
                            <div class="plan-percentage">12%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">User Geography</div>
                </div>
                <div class="geo-map">
                    <div style="text-align: center;">
                        <i class="fas fa-globe-americas" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <div>User Geographic Distribution Map</div>
                        <div style="font-size: 0.875rem; margin-top: 0.5rem;">Interactive map would appear here</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Feature Usage</div>
                </div>
                <div class="feature-usage">
                    <div class="feature-item">
                        <div class="feature-name">Dashboard</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 92%; background-color: #10b981;"></div>
                        </div>
                        <div class="feature-value">92%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Reports</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 78%; background-color: #3b82f6;"></div>
                        </div>
                        <div class="feature-value">78%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">API</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 65%; background-color: #8b5cf6;"></div>
                        </div>
                        <div class="feature-value">65%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Integrations</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 45%; background-color: #f59e0b;"></div>
                        </div>
                        <div class="feature-value">45%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Mobile App</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 38%; background-color: #ef4444;"></div>
                        </div>
                        <div class="feature-value">38%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Tab -->
    <div class="tab-content" id="revenue">
        <div class="grid-2">
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Revenue by Plan</div>
                </div>
                <canvas id="revenueByPlanChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">MRR Growth</div>
                </div>
                <canvas id="mrrChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Conversion Funnel</div>
            </div>
            <div class="conversion-funnel">
                <div class="funnel-stage">
                    <div class="funnel-number">1</div>
                    <div class="funnel-content">
                        <div class="funnel-title">Website Visitors</div>
                        <div class="funnel-description">Total visitors to the landing page</div>
                    </div>
                    <div class="funnel-stats">
                        <div class="funnel-value">24,580</div>
                        <div class="funnel-percentage">100%</div>
                    </div>
                </div>
                <div class="funnel-stage">
                    <div class="funnel-number">2</div>
                    <div class="funnel-content">
                        <div class="funnel-title">Sign-ups</div>
                        <div class="funnel-description">Users who created an account</div>
                    </div>
                    <div class="funnel-stats">
                        <div class="funnel-value">3,245</div>
                        <div class="funnel-percentage">13.2%</div>
                    </div>
                </div>
                <div class="funnel-stage">
                    <div class="funnel-number">3</div>
                    <div class="funnel-content">
                        <div class="funnel-title">Activated Users</div>
                        <div class="funnel-description">Users who completed onboarding</div>
                    </div>
                    <div class="funnel-stats">
                        <div class="funnel-value">1,872</div>
                        <div class="funnel-percentage">7.6%</div>
                    </div>
                </div>
                <div class="funnel-stage">
                    <div class="funnel-number">4</div>
                    <div class="funnel-content">
                        <div class="funnel-title">Paying Customers</div>
                        <div class="funnel-description">Users with active subscriptions</div>
                    </div>
                    <div class="funnel-stats">
                        <div class="funnel-value">1,248</div>
                        <div class="funnel-percentage">5.1%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Tab -->
    <div class="tab-content" id="users">
        <div class="grid-2">
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">User Acquisition</div>
                </div>
                <canvas id="acquisitionChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">User Retention</div>
                </div>
                <canvas id="retentionChart"></canvas>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Top User Segments</div>
                </div>
                <div class="feature-usage">
                    <div class="feature-item">
                        <div class="feature-name">Power Users</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 28%; background-color: #10b981;"></div>
                        </div>
                        <div class="feature-value">28%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Regular Users</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 45%; background-color: #3b82f6;"></div>
                        </div>
                        <div class="feature-value">45%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Occasional Users</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 18%; background-color: #8b5cf6;"></div>
                        </div>
                        <div class="feature-value">18%</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-name">Inactive Users</div>
                        <div class="feature-bar">
                            <div class="feature-progress" style="width: 9%; background-color: #ef4444;"></div>
                        </div>
                        <div class="feature-value">9%</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent User Activity</div>
                </div>
                <ul class="recent-activities">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New user registered</div>
                            <div class="activity-description">Sarah Johnson signed up for Pro plan</div>
                        </div>
                        <div class="activity-time">2 hours ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Subscription upgraded</div>
                            <div class="activity-description">Michael Brown upgraded to Enterprise</div>
                        </div>
                        <div class="activity-time">5 hours ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Data exported</div>
                            <div class="activity-description">Emily Davis exported analytics report</div>
                        </div>
                        <div class="activity-time">1 day ago</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Engagement Tab -->
    <div class="tab-content" id="engagement">
        <div class="grid-2">
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Session Duration</div>
                </div>
                <canvas id="sessionChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Feature Engagement</div>
                </div>
                <canvas id="engagementChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">User Engagement Metrics</div>
            </div>
            <div class="grid-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">Daily Active Users</div>
                        <div class="stat-card-icon" style="background-color: #3b82f6;">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">842</div>
                    <div class="stat-card-desc">
                        <span class="positive">+5.2%</span> from last week
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">Weekly Active Users</div>
                        <div class="stat-card-icon" style="background-color: #8b5cf6;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">1,128</div>
                    <div class="stat-card-desc">
                        <span class="positive">+3.7%</span> from last week
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">Monthly Active Users</div>
                        <div class="stat-card-icon" style="background-color: #f59e0b;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">1,248</div>
                    <div class="stat-card-desc">
                        <span class="positive">+8.2%</span> from last month
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle sidebar
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Toggle theme
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark');
            const icon = this.querySelector('i');
            if (document.body.classList.contains('dark')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });

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

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [18500, 20200, 19800, 21500, 23800, 24580],
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
                        beginAtZero: false
                    }
                }
            }
        });

        // User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        const userGrowthChart = new Chart(userGrowthCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Users',
                    data: [210, 185, 240, 195, 220, 248],
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                }, {
                    label: 'Active Users',
                    data: [980, 1020, 1050, 1120, 1180, 1248],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        stacked: false
                    }
                }
            }
        });

        // Revenue by Plan Chart
        const revenueByPlanCtx = document.getElementById('revenueByPlanChart').getContext('2d');
        const revenueByPlanChart = new Chart(revenueByPlanCtx, {
            type: 'doughnut',
            data: {
                labels: ['Basic', 'Pro', 'Enterprise'],
                datasets: [{
                    data: [6200, 14500, 3880],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(139, 92, 246)',
                        'rgb(245, 158, 11)'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // MRR Chart
        const mrrCtx = document.getElementById('mrrChart').getContext('2d');
        const mrrChart = new Chart(mrrCtx, {
            type: 'line',
            data: {
                labels: ['Q1 2022', 'Q2 2022', 'Q3 2022', 'Q4 2022', 'Q1 2023', 'Q2 2023'],
                datasets: [{
                    label: 'MRR ($)',
                    data: [12500, 14200, 16800, 19500, 21800, 24580],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
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