<style>
    .content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .settings-container {
        display: flex;
        gap: 2rem;
    }

    .settings-sidebar {
        width: 240px;
        flex-shrink: 0;
    }

    .settings-nav {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1rem 0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .settings-nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: hsl(var(--foreground));
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .settings-nav-item:hover {
        background-color: hsl(var(--accent));
    }

    .settings-nav-item.active {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
    }

    .settings-content {
        flex: 1;
    }

    .settings-section {
        display: none;
    }

    .settings-section.active {
        display: block;
    }

    .card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .card-description {
        color: hsl(var(--muted-foreground));
        font-size: 0.875rem;
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
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid hsl(var(--input));
        border-radius: var(--radius);
        background-color: hsl(var(--background));
        color: hsl(var(--foreground));
        font-size: 0.875rem;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: hsl(var(--ring));
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-hint {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin-top: 0.5rem;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
    }

    .form-check-label {
        font-size: 0.875rem;
        font-weight: 500;
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

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
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

    .api-key {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: hsl(var(--muted));
        padding: 0.75rem;
        border-radius: var(--radius);
        font-family: monospace;
        margin-bottom: 1rem;
    }

    .api-key-value {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .danger-zone {
        border: 1px solid hsl(var(--destructive));
        border-radius: var(--radius);
        padding: 1.5rem;
        background-color: rgba(239, 68, 68, 0.05);
    }

    .danger-zone-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: hsl(var(--destructive));
    }

    .danger-zone-title {
        font-weight: 600;
        font-size: 1.125rem;
    }

    .danger-zone-description {
        color: hsl(var(--muted-foreground));
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .integration-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        margin-bottom: 1rem;
    }

    .integration-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        background-color: hsl(var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .integration-info {
        flex: 1;
    }

    .integration-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .integration-description {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .integration-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-connected {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
    }

    .status-disconnected {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(185, 28, 28);
    }

    .team-member {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        margin-bottom: 1rem;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: hsl(var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .member-role {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .member-actions {
        display: flex;
        gap: 0.5rem;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .role-admin {
        background-color: rgba(59, 130, 246, 0.1);
        color: rgb(37, 99, 235);
    }

    .role-member {
        background-color: rgba(16, 185, 129, 0.1);
        color: rgb(5, 122, 85);
    }

    .role-viewer {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(180, 83, 9);
    }

    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .sidebar-header h2,
        .nav-item span {
            display: none;
        }

        .settings-container {
            flex-direction: column;
        }

        .settings-sidebar {
            width: 100%;
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .search-bar {
            width: 200px;
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
    <div class="settings-container">
        <!-- Settings Sidebar -->
        <div class="settings-sidebar">
            <div class="settings-nav">
                <a href="#" class="settings-nav-item active" data-section="general">
                    <i class="fas fa-cog"></i>
                    <span>General</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="team">
                    <i class="fas fa-users"></i>
                    <span>Team</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="billing">
                    <i class="fas fa-credit-card"></i>
                    <span>Billing & Plans</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="integrations">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Integrations</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="api">
                    <i class="fas fa-code"></i>
                    <span>API Keys</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="security">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security</span>
                </a>
                <a href="#" class="settings-nav-item" data-section="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="settings-content">
            <!-- General Settings -->
            <div class="settings-section active" id="general">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">General Settings</div>
                        <div class="card-description">Manage your account settings and preferences</div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-input" value="SaaS Admin Inc.">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-input" value="admin@saasadmin.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company Description</label>
                        <textarea
                            class="form-textarea">We provide a comprehensive SaaS administration platform for businesses of all sizes.</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Timezone</label>
                        <select class="form-select">
                            <option>Eastern Time (ET)</option>
                            <option selected>Central Time (CT)</option>
                            <option>Mountain Time (MT)</option>
                            <option>Pacific Time (PT)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Format</label>
                        <select class="form-select">
                            <option>MM/DD/YYYY</option>
                            <option selected>DD/MM/YYYY</option>
                            <option>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <button class="btn btn-primary">Save Changes</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Appearance</div>
                        <div class="card-description">Customize the look and feel of your dashboard</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Theme</label>
                        <div class="grid-2">
                            <div class="plan-card selected">
                                <div class="plan-header">
                                    <div class="plan-name">Light</div>
                                </div>
                                <div class="plan-features">
                                    <div class="plan-feature">
                                        <i class="fas fa-check"></i>
                                        <span>Clean, bright interface</span>
                                    </div>
                                    <div class="plan-feature">
                                        <i class="fas fa-check"></i>
                                        <span>Reduced eye strain in well-lit environments</span>
                                    </div>
                                </div>
                            </div>
                            <div class="plan-card">
                                <div class="plan-header">
                                    <div class="plan-name">Dark</div>
                                </div>
                                <div class="plan-features">
                                    <div class="plan-feature">
                                        <i class="fas fa-check"></i>
                                        <span>Reduced eye strain in low light</span>
                                    </div>
                                    <div class="plan-feature">
                                        <i class="fas fa-check"></i>
                                        <span>Modern, sleek appearance</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sidebar Style</label>
                        <select class="form-select">
                            <option selected>Expanded</option>
                            <option>Collapsed</option>
                            <option>Hidden</option>
                        </select>
                    </div>
                    <button class="btn btn-primary">Update Appearance</button>
                </div>
            </div>

            <!-- Team Settings -->
            <div class="settings-section" id="team">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Team Members</div>
                        <div class="card-description">Manage who has access to your SaaS Admin account</div>
                    </div>
                    <div class="team-member">
                        <div class="member-avatar">JD</div>
                        <div class="member-info">
                            <div class="member-name">John Doe</div>
                            <div class="member-role">john.doe@saasadmin.com</div>
                        </div>
                        <div class="role-badge role-admin">Admin</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Edit</button>
                        </div>
                    </div>
                    <div class="team-member">
                        <div class="member-avatar">SJ</div>
                        <div class="member-info">
                            <div class="member-name">Sarah Johnson</div>
                            <div class="member-role">sarah.j@saasadmin.com</div>
                        </div>
                        <div class="role-badge role-member">Member</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Edit</button>
                        </div>
                    </div>
                    <div class="team-member">
                        <div class="member-avatar">MB</div>
                        <div class="member-info">
                            <div class="member-name">Michael Brown</div>
                            <div class="member-role">m.brown@saasadmin.com</div>
                        </div>
                        <div class="role-badge role-viewer">Viewer</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Edit</button>
                        </div>
                    </div>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Invite Team Member
                    </button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Role Permissions</div>
                        <div class="card-description">Configure what each role can do in your account</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Permissions</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="admin-full" checked>
                            <label class="form-check-label" for="admin-full">Full access to all settings and
                                data</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Member Permissions</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="member-users" checked>
                            <label class="form-check-label" for="member-users">Manage users</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="member-analytics" checked>
                            <label class="form-check-label" for="member-analytics">View analytics</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="member-billing">
                            <label class="form-check-label" for="member-billing">Manage billing</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Viewer Permissions</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="viewer-dashboard" checked>
                            <label class="form-check-label" for="viewer-dashboard">View dashboard</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="viewer-analytics" checked>
                            <label class="form-check-label" for="viewer-analytics">View analytics</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="viewer-users">
                            <label class="form-check-label" for="viewer-users">View users</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Save Permissions</button>
                </div>
            </div>

            <!-- Billing & Plans -->
            <div class="settings-section" id="billing">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Current Plan</div>
                        <div class="card-description">Manage your subscription and billing information</div>
                    </div>
                    <div class="plan-card selected">
                        <div class="plan-header">
                            <div class="plan-name">Business Plan</div>
                            <div class="plan-price">$99/month</div>
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
                        </ul>
                        <div class="form-group">
                            <label class="form-label">Billing Cycle</label>
                            <select class="form-select">
                                <option>Monthly</option>
                                <option selected>Yearly (Save 15%)</option>
                            </select>
                        </div>
                        <button class="btn btn-outline" style="margin-right: 0.5rem;">Change Plan</button>
                        <button class="btn btn-primary">Update Billing</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Payment Method</div>
                        <div class="card-description">Update your payment details</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-input" value="**** **** **** 4242">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Expiration Date</label>
                            <input type="text" class="form-input" value="12/2024">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Security Code</label>
                            <input type="text" class="form-input" value="***">
                        </div>
                    </div>
                    <button class="btn btn-primary">Update Payment Method</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Billing History</div>
                        <div class="card-description">View and download your past invoices</div>
                    </div>
                    <div class="form-group">
                        <p>Your billing history is available for download. You can access invoices from the past 24
                            months.</p>
                    </div>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download Invoices
                    </button>
                </div>
            </div>

            <!-- Integrations -->
            <div class="settings-section" id="integrations">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Connected Apps</div>
                        <div class="card-description">Manage your third-party integrations</div>
                    </div>
                    <div class="integration-card">
                        <div class="integration-icon" style="background-color: #4285F4;">
                            <i class="fab fa-google" style="color: white;"></i>
                        </div>
                        <div class="integration-info">
                            <div class="integration-name">Google Workspace</div>
                            <div class="integration-description">Sync user accounts and manage permissions</div>
                        </div>
                        <div class="integration-status status-connected">Connected</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Configure</button>
                        </div>
                    </div>
                    <div class="integration-card">
                        <div class="integration-icon" style="background-color: #00A4EF;">
                            <i class="fab fa-microsoft" style="color: white;"></i>
                        </div>
                        <div class="integration-info">
                            <div class="integration-name">Microsoft 365</div>
                            <div class="integration-description">Single sign-on and directory sync</div>
                        </div>
                        <div class="integration-status status-connected">Connected</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Configure</button>
                        </div>
                    </div>
                    <div class="integration-card">
                        <div class="integration-icon" style="background-color: #FF6B6B;">
                            <i class="fab fa-slack" style="color: white;"></i>
                        </div>
                        <div class="integration-info">
                            <div class="integration-name">Slack</div>
                            <div class="integration-description">Receive notifications and alerts</div>
                        </div>
                        <div class="integration-status status-disconnected">Disconnected</div>
                        <div class="member-actions">
                            <button class="btn btn-outline btn-sm">Connect</button>
                        </div>
                    </div>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add New Integration
                    </button>
                </div>
            </div>

            <!-- API Keys -->
            <div class="settings-section" id="api">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">API Keys</div>
                        <div class="card-description">Manage your API keys for programmatic access</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primary API Key</label>
                        <div class="api-key">
                            <div class="api-key-value">sk_live_51Mn8s2KZqXr8x6Y9vA7bNc3...</div>
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                        <div class="form-hint">This key has full access to your account. Keep it secure.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Create New API Key</label>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Key Name</label>
                                <input type="text" class="form-input" placeholder="e.g., Development Key">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Permissions</label>
                                <select class="form-select">
                                    <option>Read Only</option>
                                    <option>Read & Write</option>
                                    <option>Full Access</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Generate API Key</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">API Usage</div>
                        <div class="card-description">Monitor your API request usage and limits</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Current Usage</label>
                        <div class="form-hint">12,458 requests this month (50,000 limit)</div>
                        <div
                            style="height: 8px; background-color: hsl(var(--muted)); border-radius: 4px; margin-top: 0.5rem; overflow: hidden;">
                            <div style="width: 25%; height: 100%; background-color: #10b981; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reset Date</label>
                        <div class="form-hint">Your API usage will reset on July 1, 2023</div>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="settings-section" id="security">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Security Settings</div>
                        <div class="card-description">Manage your account security and access controls</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Two-Factor Authentication</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="2fa" checked>
                            <label class="form-check-label" for="2fa">Enable two-factor authentication</label>
                        </div>
                        <div class="form-hint">Add an extra layer of security to your account</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Session Timeout</label>
                        <select class="form-select">
                            <option>15 minutes</option>
                            <option>30 minutes</option>
                            <option selected>1 hour</option>
                            <option>4 hours</option>
                            <option>24 hours</option>
                        </select>
                        <div class="form-hint">Automatically log out after period of inactivity</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Login Notifications</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="login-notifications" checked>
                            <label class="form-check-label" for="login-notifications">Send email notifications for new
                                logins</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Update Security Settings</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Active Sessions</div>
                        <div class="card-description">Manage your active login sessions</div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="current-session" checked>
                            <label class="form-check-label" for="current-session">
                                <strong>Current Session</strong> - Chrome on Windows • New York, USA
                                <div class="form-hint">Active now • IP: 192.168.1.1</div>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="other-session">
                            <label class="form-check-label" for="other-session">
                                <strong>Mobile Session</strong> - Safari on iPhone • Chicago, USA
                                <div class="form-hint">2 hours ago • IP: 192.168.1.2</div>
                            </label>
                        </div>
                    </div>
                    <button class="btn btn-outline">Sign Out Other Sessions</button>
                </div>

                <div class="danger-zone">
                    <div class="danger-zone-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="danger-zone-title">Danger Zone</div>
                    </div>
                    <div class="danger-zone-description">
                        Once you delete your account, there is no going back. Please be certain.
                    </div>
                    <button class="btn"
                        style="background-color: hsl(var(--destructive)); color: hsl(var(--destructive-foreground));">
                        Delete Account
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div class="settings-section" id="notifications">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Notification Preferences</div>
                        <div class="card-description">Configure how you receive notifications</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Notifications</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="email-security" checked>
                            <label class="form-check-label" for="email-security">Security alerts</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="email-billing" checked>
                            <label class="form-check-label" for="email-billing">Billing and invoices</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="email-product">
                            <label class="form-check-label" for="email-product">Product updates</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="email-marketing">
                            <label class="form-check-label" for="email-marketing">Marketing communications</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Push Notifications</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="push-new-users" checked>
                            <label class="form-check-label" for="push-new-users">New user signups</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="push-errors">
                            <label class="form-check-label" for="push-errors">System errors</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="push-updates">
                            <label class="form-check-label" for="push-updates">Feature updates</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Save Preferences</button>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
<script>
    // Toggle sidebar
        // document.querySelector('.toggle-sidebar').addEventListener('click', function() {
        //     document.querySelector('.sidebar').classList.toggle('collapsed');
        // });

        // // Toggle theme
        // document.querySelector('.theme-toggle').addEventListener('click', function() {
        //     document.body.classList.toggle('dark');
        //     const icon = this.querySelector('i');
        //     if (document.body.classList.contains('dark')) {
        //         icon.classList.remove('fa-moon');
        //         icon.classList.add('fa-sun');
        //     } else {
        //         icon.classList.remove('fa-sun');
        //         icon.classList.add('fa-moon');
        //     }
        // });

        // Settings navigation
        document.querySelectorAll('.settings-nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all nav items and sections
                document.querySelectorAll('.settings-nav-item').forEach(nav => nav.classList.remove('active'));
                document.querySelectorAll('.settings-section').forEach(section => section.classList.remove('active'));
                
                // Add active class to clicked nav item and corresponding section
                this.classList.add('active');
                const sectionId = this.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
            });
        });

        // Plan selection
        document.querySelectorAll('.plan-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Mobile sidebar toggle
        // if (window.innerWidth <= 640) {
        //     document.querySelector('.toggle-sidebar').addEventListener('click', function() {
        //         document.querySelector('.sidebar').classList.toggle('open');
        //     });
        // }
</script>