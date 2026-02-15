<style>
    .content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .hero-section {
        background: linear-gradient(135deg, hsl(var(--primary)) 0%, #7c3aed 100%);
        border-radius: var(--radius);
        padding: 3rem;
        color: white;
        margin-bottom: 2rem;
        text-align: center;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.125rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-search {
        display: flex;
        max-width: 500px;
        margin: 0 auto;
    }

    .hero-search input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: var(--radius) 0 0 var(--radius);
        font-size: 1rem;
    }

    .hero-search button {
        background-color: hsl(var(--background));
        color: hsl(var(--foreground));
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 0 var(--radius) var(--radius) 0;
        cursor: pointer;
        font-weight: 500;
    }


    .support-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: hsl(var(--primary));
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: hsl(var(--muted-foreground));
        font-size: 0.875rem;
    }

    .help-sections {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .help-card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 2rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .help-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: hsl(var(--primary));
    }

    .help-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius);
        background-color: rgba(59, 130, 246, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: hsl(var(--primary));
        font-size: 1.5rem;
    }

    .help-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .help-description {
        color: hsl(var(--muted-foreground));
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .help-link {
        color: hsl(var(--primary));
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tickets-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
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
        text-decoration: none;
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

    .tickets-grid {
        display: grid;
        gap: 1rem;
    }

    .ticket-card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ticket-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        background-color: hsl(var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ticket-content {
        flex: 1;
    }

    .ticket-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .ticket-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }

    .ticket-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-open {
        background-color: rgba(59, 130, 246, 0.1);
        color: rgb(37, 99, 235);
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(180, 83, 9);
    }

    .status-resolved {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
    }

    .status-closed {
        background-color: rgba(107, 114, 128, 0.1);
        color: rgb(107, 114, 128);
    }

    .knowledge-base {
        margin-bottom: 2rem;
    }

    .kb-categories {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .kb-category {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 1.5rem;
    }

    .kb-category-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .kb-category-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        background-color: rgba(59, 130, 246, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: hsl(var(--primary));
        font-size: 1.25rem;
    }

    .kb-category-title {
        font-weight: 600;
        font-size: 1.125rem;
    }

    .kb-articles {
        list-style: none;
    }

    .kb-article {
        padding: 0.75rem 0;
        border-bottom: 1px solid hsl(var(--border));
    }

    .kb-article:last-child {
        border-bottom: none;
    }

    .kb-article a {
        color: hsl(var(--foreground));
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .kb-article a:hover {
        color: hsl(var(--primary));
    }

    .contact-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .contact-card {
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        padding: 2rem;
        text-align: center;
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, hsl(var(--primary)) 0%, #7c3aed 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }

    .contact-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .contact-description {
        color: hsl(var(--muted-foreground));
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .contact-details {
        margin-top: 1rem;
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
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

    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .sidebar-header h2,
        .nav-item span {
            display: none;
        }

        .help-sections,
        .kb-categories,
        .contact-methods {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .search-bar {
            width: 200px;
        }

        .support-stats {
            grid-template-columns: repeat(2, 1fr);
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

        .support-stats {
            grid-template-columns: 1fr;
        }

        .hero-section {
            padding: 2rem 1rem;
        }

        .hero-title {
            font-size: 1.75rem;
        }
    }
</style>

<div>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">How can we help you?</h1>
        <p class="hero-description">Find answers to common questions, browse documentation, or get support from our
            team.</p>
        <div class="hero-search">
            <input type="text" placeholder="Search for answers...">
            <button>Search</button>
        </div>
    </div>

    <!-- Support Stats -->
    <div class="support-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">2.1h</div>
            <div class="stat-label">Avg. Response Time</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">98%</div>
            <div class="stat-label">Satisfaction Rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-value">24</div>
            <div class="stat-label">Open Tickets</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-value">156</div>
            <div class="stat-label">Help Articles</div>
        </div>
    </div>

    <!-- Help Sections -->
    <div class="help-sections">
        <div class="help-card">
            <div class="help-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3 class="help-title">Knowledge Base</h3>
            <p class="help-description">Browse our comprehensive documentation and find answers to common questions.</p>
            <a href="#" class="help-link">
                <span>Browse Articles</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="help-card">
            <div class="help-icon">
                <i class="fas fa-video"></i>
            </div>
            <h3 class="help-title">Video Tutorials</h3>
            <p class="help-description">Watch step-by-step tutorials to master our platform's features.</p>
            <a href="#" class="help-link">
                <span>Watch Videos</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="help-card">
            <div class="help-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="help-title">Community Forum</h3>
            <p class="help-description">Connect with other users and share tips, tricks, and best practices.</p>
            <a href="#" class="help-link">
                <span>Join Community</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="tickets-section">
        <div class="section-header">
            <h2 class="section-title">Recent Support Tickets</h2>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Ticket
            </a>
        </div>
        <div class="tickets-grid">
            <div class="ticket-card">
                <div class="ticket-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="ticket-content">
                    <div class="ticket-title">Unable to process payments</div>
                    <div class="ticket-meta">
                        <span>#TKT-7842</span>
                        <span>Created: 2 hours ago</span>
                    </div>
                </div>
                <div class="ticket-status status-open">Open</div>
            </div>
            <div class="ticket-card">
                <div class="ticket-icon">
                    <i class="fas fa-bug"></i>
                </div>
                <div class="ticket-content">
                    <div class="ticket-title">Dashboard loading slowly</div>
                    <div class="ticket-meta">
                        <span>#TKT-7839</span>
                        <span>Created: 1 day ago</span>
                    </div>
                </div>
                <div class="ticket-status status-pending">Pending</div>
            </div>
            <div class="ticket-card">
                <div class="ticket-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="ticket-content">
                    <div class="ticket-title">API integration question</div>
                    <div class="ticket-meta">
                        <span>#TKT-7835</span>
                        <span>Created: 2 days ago</span>
                    </div>
                </div>
                <div class="ticket-status status-resolved">Resolved</div>
            </div>
            <div class="ticket-card">
                <div class="ticket-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ticket-content">
                    <div class="ticket-title">User permission issue</div>
                    <div class="ticket-meta">
                        <span>#TKT-7828</span>
                        <span>Created: 3 days ago</span>
                    </div>
                </div>
                <div class="ticket-status status-closed">Closed</div>
            </div>
        </div>
    </div>

    <!-- Knowledge Base -->
    <div class="knowledge-base">
        <div class="section-header">
            <h2 class="section-title">Knowledge Base</h2>
            <a href="#" class="btn btn-outline">View All Articles</a>
        </div>
        <div class="kb-categories">
            <div class="kb-category">
                <div class="kb-category-header">
                    <div class="kb-category-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="kb-category-title">Getting Started</h3>
                </div>
                <ul class="kb-articles">
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Setting up your account</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Inviting team members</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Understanding the dashboard</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Basic configuration guide</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="kb-category">
                <div class="kb-category-header">
                    <div class="kb-category-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="kb-category-title">Features & Usage</h3>
                </div>
                <ul class="kb-articles">
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>User management guide</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Analytics and reporting</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Billing and subscriptions</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>API integration guide</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="kb-category">
                <div class="kb-category-header">
                    <div class="kb-category-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="kb-category-title">Security & Privacy</h3>
                </div>
                <ul class="kb-articles">
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Security best practices</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Two-factor authentication</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>Data privacy and GDPR</span>
                        </a>
                    </li>
                    <li class="kb-article">
                        <a href="#">
                            <i class="fas fa-file-alt"></i>
                            <span>User permission levels</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Contact Methods -->
    <div class="section-header">
        <h2 class="section-title">Contact Support</h2>
    </div>
    <div class="contact-methods">
        <div class="contact-card">
            <div class="contact-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="contact-title">Live Chat</h3>
            <p class="contact-description">Get instant help from our support team through live chat.</p>
            <button class="btn btn-primary">Start Chat</button>
            <div class="contact-details">
                Available 9AM-6PM EST, Mon-Fri
            </div>
        </div>
        <div class="contact-card">
            <div class="contact-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h3 class="contact-title">Email Support</h3>
            <p class="contact-description">Send us an email and we'll get back to you within a few hours.</p>
            <button class="btn btn-outline">Send Email</button>
            <div class="contact-details">
                support@saasadmin.com
            </div>
        </div>
        <div class="contact-card">
            <div class="contact-icon">
                <i class="fas fa-phone"></i>
            </div>
            <h3 class="contact-title">Phone Support</h3>
            <p class="contact-description">Speak directly with our support team for urgent issues.</p>
            <button class="btn btn-outline">Call Now</button>
            <div class="contact-details">
                +1 (555) 123-HELP
            </div>
        </div>
    </div>
</div>

<script>
    // Help card interactions
        document.querySelectorAll('.help-card').forEach(card => {
            card.addEventListener('click', function() {
                const link = this.querySelector('.help-link');
                if (link) {
                    window.location.href = link.getAttribute('href');
                }
            });
        });
</script>