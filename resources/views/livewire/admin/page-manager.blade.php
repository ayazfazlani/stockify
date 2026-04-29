<div>
    <style>
        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .cms-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: hsl(var(--foreground));
        }

        .cms-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .cms-filters .form-input,
        .cms-filters .form-select {
            max-width: 280px;
        }

        .cms-table {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .cms-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .cms-table th,
        .cms-table td {
            padding: 1rem 1.25rem;
            text-align: left;
            border-bottom: 1px solid hsl(var(--border));
            font-size: 0.875rem;
        }

        .cms-table th {
            font-weight: 600;
            color: hsl(var(--muted-foreground));
            background-color: hsl(var(--muted));
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-published {
            background-color: rgba(34, 197, 94, 0.1);
            color: rgb(21, 128, 61);
        }

        .status-draft {
            background-color: rgba(245, 158, 11, 0.1);
            color: rgb(180, 83, 9);
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius);
            border: 1px solid hsl(var(--border));
            background: transparent;
            color: hsl(var(--muted-foreground));
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--foreground));
        }

        .btn-icon.danger:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgb(185, 28, 28);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .cms-modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1rem;
            animation: cmsFadeIn 0.2s ease;
        }

        .cms-modal-container {
            background-color: hsl(var(--card));
            border-radius: calc(var(--radius) * 2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-height: 90vh;
            overflow-y: auto;
            width: 100%;
            max-width: 56rem;
            animation: cmsSlideUp 0.3s ease;
        }

        .cms-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid hsl(var(--border));
        }

        .cms-modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: hsl(var(--foreground));
        }

        .cms-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: hsl(var(--muted-foreground));
            padding: 0.5rem;
            border-radius: var(--radius);
            transition: all 0.2s;
        }

        .cms-modal-close:hover {
            background-color: hsl(var(--muted));
            color: hsl(var(--foreground));
        }

        .cms-modal-body {
            padding: 1.5rem;
        }

        .cms-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid hsl(var(--border));
            background-color: hsl(var(--muted));
            border-radius: 0 0 calc(var(--radius) * 2) calc(var(--radius) * 2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .seo-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid hsl(var(--border));
        }

        .seo-section h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: hsl(var(--primary));
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal-box {
            background: hsl(var(--card));
            border-radius: var(--radius);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }

        .modal-box h3 {
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .modal-box p {
            color: hsl(var(--muted-foreground));
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn-danger {
            background-color: hsl(var(--destructive));
            color: hsl(var(--destructive-foreground));
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-danger:hover {
            opacity: 0.9;
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

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: hsl(var(--muted-foreground));
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @keyframes cmsFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes cmsSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .cms-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .cms-filters {
                flex-direction: column;
            }

            .cms-filters .form-input,
            .cms-filters .form-select {
                max-width: 100%;
            }
        }
    </style>


    @if (session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- LIST VIEW (always visible) --}}
    <div class="cms-header">
        <h2><i class="fas fa-file-alt" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> CMS Pages</h2>
        <button class="btn btn-primary" wire:click.prevent="create">
            <i class="fas fa-plus"></i> New Page
        </button>
    </div>

    <div class="cms-filters">
        <input type="text" class="form-input" wire:model.live.debounce.300ms="search" placeholder="Search pages...">
        <select class="form-input" wire:model.live="filterStatus" style="max-width: 180px;">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <div class="cms-table">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>SEO</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td>
                            <strong>{{ $page->title }}</strong>
                            @if($page->is_system)
                                <span class="status-badge"
                                    style="background: rgba(99,102,241,0.1); color: rgb(79,70,229); margin-left: 0.5rem;">System</span>
                            @endif
                        </td>
                        <td><code
                                style="font-size: 0.8rem; background: hsl(var(--muted)); padding: 0.125rem 0.5rem; border-radius: 4px;">/{{ $page->slug }}</code>
                        </td>
                        <td>
                            <button wire:click="toggleStatus({{ $page->id }})"
                                class="status-badge {{ $page->status === 'published' ? 'status-published' : 'status-draft' }}"
                                style="cursor: pointer; border: none;">
                                {{ ucfirst($page->status) }}
                            </button>
                        </td>
                        <td>
                            @if($page->meta_title)
                                <i class="fas fa-check-circle" style="color: rgb(21, 128, 61);" title="Meta title set"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: rgb(185, 28, 28);" title="No meta title"></i>
                            @endif
                            @if($page->meta_description)
                                <i class="fas fa-check-circle" style="color: rgb(21, 128, 61);"
                                    title="Meta description set"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: rgb(185, 28, 28);" title="No meta description"></i>
                            @endif
                            @if($page->schema_markup)
                                <i class="fas fa-check-circle" style="color: rgb(21, 128, 61);" title="Schema set"></i>
                            @else
                                <i class="fas fa-minus-circle" style="color: hsl(var(--muted-foreground));"
                                    title="No schema"></i>
                            @endif
                        </td>
                        <td style="font-size: 0.8rem; color: hsl(var(--muted-foreground));">
                            {{ $page->updated_at->diffForHumans() }}</td>
                        <td>
                            <div class="action-btns">
                                @if($page->status === 'published')
                                    <a href="{{ url('/' . $page->slug) }}" target="_blank" class="btn-icon" title="View">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                                <button wire:click="edit({{ $page->id }})" class="btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @unless($page->is_system)
                                    <button wire:click="confirmDelete({{ $page->id }})" class="btn-icon danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <p>No pages found. Create your first page!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $pages->links() }}
    </div>

    {{-- CREATE / EDIT MODAL --}}
    @if ($showForm)
        <div class="cms-modal-overlay" wire:click.self="cancel">
            <div class="cms-modal-container">
                <div class="cms-modal-header">
                    <div>
                        <h3 class="cms-modal-title">
                            <i class="fas fa-file-alt" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i>
                            {{ $editingId ? 'Edit Page' : 'Create New Page' }}
                        </h3>
                    </div>
                    <button class="cms-modal-close" wire:click="cancel">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="cms-modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Page Title *</label>
                                <input type="text" class="form-input" wire:model.live="title"
                                    placeholder="Enter page title">
                                @error('title') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Slug *</label>
                                <input type="text" class="form-input" wire:model="slug" placeholder="page-slug">
                                @if($slug)
                                <div class="slug-preview">URL: {{ url('/' . $slug) }}</div>@endif
                                @error('slug') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Page Content</label>
                            <textarea class="form-input" wire:model="body" rows="12"
                                placeholder="Enter page content (HTML supported)"
                                style="min-height: 200px; font-family: monospace; font-size: 0.8rem;"></textarea>
                            @error('body') <span
                            style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-input" wire:model="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-input" wire:model="sort_order" min="0">
                            </div>
                        </div>

                        {{-- SEO Section --}}
                        <div class="seo-section">
                            <h4><i class="fas fa-search"></i> SEO Settings</h4>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-input" wire:model="meta_title"
                                        placeholder="SEO title (max 60 chars)" maxlength="255">
                                    <div class="slug-preview">{{ strlen($meta_title) }}/60 characters</div>
                                    @error('meta_title') <span
                                        style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Canonical URL</label>
                                    <input type="url" class="form-input" wire:model="canonical_url"
                                        placeholder="https://...">
                                    @error('canonical_url') <span
                                        style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-input" wire:model="meta_description" rows="3"
                                    placeholder="SEO description (max 160 chars)" maxlength="500"></textarea>
                                <div class="slug-preview">{{ strlen($meta_description) }}/160 characters recommended</div>
                                @error('meta_description') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-input" wire:model="schema_markup" rows="6"
                                    placeholder='{"@context": "https://schema.org", "@type": "WebPage", ...}'
                                    style="font-family: monospace; font-size: 0.8rem;"></textarea>
                                @error('schema_markup') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">OG Image</label>
                                <input type="file" class="form-input" wire:model="og_image" accept="image/*">
                                <div class="slug-preview">Recommended: 1200x630px</div>
                            </div>
                        </div>
                    </div>

                    <div class="cms-modal-footer">
                        <button type="button" class="btn btn-outline" wire:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save"></i> {{ $editingId ? 'Update Page' : 'Create Page' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fas fa-spinner fa-spin"></i> {{ $editingId ? 'Updating...' : 'Creating...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteConfirm)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3><i class="fas fa-exclamation-triangle" style="color: hsl(var(--destructive));"></i> Delete Page</h3>
                <p>Are you sure you want to delete this page? This action can be undone (soft delete).</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" wire:click="cancelDelete">Cancel</button>
                    <button class="btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>