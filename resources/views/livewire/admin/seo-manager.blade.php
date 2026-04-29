<div>
    <style>
        .seo-tabs {
            display: flex;
            gap: 0.25rem;
            border-bottom: 2px solid hsl(var(--border));
            margin-bottom: 2rem;
        }

        .seo-tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            color: hsl(var(--muted-foreground));
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: -2px;
        }

        .seo-tab.active {
            color: hsl(var(--primary));
            border-bottom-color: hsl(var(--primary));
        }

        .seo-tab:hover {
            color: hsl(var(--foreground));
        }

        .seo-tab i {
            margin-right: 0.5rem;
        }

        .seo-panel {
            display: none;
        }

        .seo-panel.active {
            display: block;
        }

        .cms-form-card {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .cms-form-card .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .cms-form-card .card-desc {
            color: hsl(var(--muted-foreground));
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: rgb(21, 128, 61);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgb(185, 28, 28);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .slug-preview {
            font-size: 0.8rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }

        .robots-editor {
            font-family: monospace;
            font-size: 0.85rem;
            min-height: 300px;
            background: hsl(var(--background));
            line-height: 1.6;
        }

        .sitemap-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .sitemap-stat {
            background: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
        }

        .sitemap-stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: hsl(var(--primary));
        }

        .sitemap-stat-label {
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }

        .info-box {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-box i {
            color: hsl(var(--primary));
            margin-top: 0.125rem;
        }

        .info-box p {
            font-size: 0.875rem;
            color: hsl(var(--foreground));
            line-height: 1.5;
        }

        .sitemap-url {
            font-family: monospace;
            font-size: 0.85rem;
            background: hsl(var(--muted));
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sitemap-url a {
            color: hsl(var(--primary));
            text-decoration: none;
        }

        .sitemap-url a:hover {
            text-decoration: underline;
        }

        .seo-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .seo-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .sitemap-stats {
                grid-template-columns: 1fr;
            }

            .seo-tabs {
                overflow-x: auto;
            }
        }
    </style>


    @if (session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="seo-header">
        <i class="fas fa-search" style="font-size: 1.5rem; color: hsl(var(--primary));"></i>
        <h2>SEO & Sitemap Manager</h2>
    </div>

    {{-- Tabs --}}
    <div class="seo-tabs">
        <button class="seo-tab {{ $activeTab === 'robots' ? 'active' : '' }}" wire:click="setTab('robots')">
            <i class="fas fa-robot"></i> Robots.txt
        </button>
        <button class="seo-tab {{ $activeTab === 'sitemap' ? 'active' : '' }}" wire:click="setTab('sitemap')">
            <i class="fas fa-sitemap"></i> Sitemap
        </button>
        <button class="seo-tab {{ $activeTab === 'global' ? 'active' : '' }}" wire:click="setTab('global')">
            <i class="fas fa-globe"></i> Global SEO
        </button>
    </div>

    {{-- Robots.txt Panel --}}
    <div class="seo-panel {{ $activeTab === 'robots' ? 'active' : '' }}">
        <div class="cms-form-card">
            <div class="card-title"><i class="fas fa-robot"
                    style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> Robots.txt</div>
            <div class="card-desc">Control how search engines crawl your website. Changes take effect immediately.</div>

            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>The <code>robots.txt</code> file instructs search engine crawlers which pages they can or cannot
                    access. Be careful when modifying — incorrect rules can deindex your entire site.</p>
            </div>

            <div class="sitemap-url">
                <a href="{{ url('/robots.txt') }}" target="_blank">{{ url('/robots.txt') }}</a>
                <a href="{{ url('/robots.txt') }}" target="_blank" style="font-size: 0.8rem;"><i
                        class="fas fa-external-link-alt"></i></a>
            </div>

            <div class="form-group">
                <label class="form-label">Robots.txt Content</label>
                <textarea class="form-input robots-editor" wire:model="robots_txt" rows="12"></textarea>
                @error('robots_txt') <span
                style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" wire:click="saveRobotsTxt">
                    <i class="fas fa-save"></i> Save Robots.txt
                </button>
            </div>
        </div>
    </div>

    {{-- Sitemap Panel --}}
    <div class="seo-panel {{ $activeTab === 'sitemap' ? 'active' : '' }}">
        <div class="sitemap-stats">
            <div class="sitemap-stat">
                <div class="sitemap-stat-value">{{ $publishedPages }}</div>
                <div class="sitemap-stat-label">Published Pages</div>
            </div>
            <div class="sitemap-stat">
                <div class="sitemap-stat-value">{{ $publishedPosts }}</div>
                <div class="sitemap-stat-label">Published Posts</div>
            </div>
            <div class="sitemap-stat">
                <div class="sitemap-stat-value">{{ $publishedPages + $publishedPosts + 1 }}</div>
                <div class="sitemap-stat-label">Total Sitemap URLs</div>
            </div>
        </div>

        <div class="cms-form-card">
            <div class="card-title"><i class="fas fa-sitemap"
                    style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> XML Sitemap</div>
            <div class="card-desc">Your sitemap is automatically generated and updated when pages or posts are
                published.</div>

            <div class="sitemap-url">
                <a href="{{ url('/sitemap.xml') }}" target="_blank">{{ url('/sitemap.xml') }}</a>
                <a href="{{ url('/sitemap.xml') }}" target="_blank" style="font-size: 0.8rem;"><i
                        class="fas fa-external-link-alt"></i></a>
            </div>

            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p><strong>Cache Status:</strong> {{ $lastGenerated }}<br>
                    The sitemap is cached for performance. It auto-invalidates when you publish, update, or delete any
                    page or blog post. You can also manually regenerate it below.</p>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" wire:click="regenerateSitemap">
                    <i class="fas fa-sync-alt"></i> Regenerate Sitemap
                </button>
                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline">
                    <i class="fas fa-external-link-alt"></i> View Sitemap
                </a>
            </div>
        </div>
    </div>

    {{-- Global SEO Panel --}}
    <div class="seo-panel {{ $activeTab === 'global' ? 'active' : '' }}">
        <div class="cms-form-card">
            <div class="card-title"><i class="fas fa-globe"
                    style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> Global SEO Settings</div>
            <div class="card-desc">Configure site-wide SEO defaults. These apply when individual pages don't have their
                own settings.</div>

            <div class="form-group">
                <label class="form-label">Default Meta Title Template</label>
                <input type="text" class="form-input" wire:model="default_meta_title"
                    placeholder="{{page_title}} | StockFlow">
                <div class="slug-preview">Use <code>@{{page_title}}</code> as a placeholder for the page/post title.
                </div>
                @error('default_meta_title') <span
                style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Site Description</label>
                <textarea class="form-input" wire:model="site_description" rows="3"
                    placeholder="Default site description for search engines" maxlength="500"></textarea>
                <div class="slug-preview">{{ strlen($site_description) }}/160 characters recommended</div>
                @error('site_description') <span
                style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Organization Schema Markup (JSON-LD)</label>
                <textarea class="form-input robots-editor" wire:model="global_schema" rows="10"
                    placeholder='{"@context": "https://schema.org", "@type": "Organization", ...}'></textarea>
                <div class="slug-preview">This schema is included on all pages that don't define their own schema.</div>
                @error('global_schema') <span
                style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" wire:click="saveGlobalSeo">
                    <i class="fas fa-save"></i> Save Global Settings
                </button>
            </div>
        </div>
    </div>
</div>