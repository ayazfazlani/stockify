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

        .cms-filters .form-input {
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

        .featured-badge {
            background-color: rgba(168, 85, 247, 0.1);
            color: rgb(126, 34, 206);
        }

        .category-badge {
            background-color: rgba(59, 130, 246, 0.1);
            color: rgb(37, 99, 235);
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
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

        .btn-icon.featured {
            color: rgb(168, 85, 247);
            border-color: rgba(168, 85, 247, 0.3);
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

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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

        .image-preview {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid hsl(var(--border));
        }

        .views-count {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            color: hsl(var(--muted-foreground));
        }

        .form-check-inline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .form-check-inline input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: hsl(var(--primary));
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

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .cms-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
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
        <h2><i class="fas fa-blog" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> Blog Posts</h2>
        <button type="button" class="btn btn-primary" wire:click="create">
            <i class="fas fa-plus"></i> New Post
        </button>
    </div>


    <div class="cms-filters">
        <input type="text" class="form-input" wire:model.live.debounce.300ms="search" placeholder="Search posts...">
        <select class="form-input" wire:model.live="filterStatus" style="max-width: 160px;">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
        <select class="form-input" wire:model.live="filterCategory" style="max-width: 200px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="cms-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>SEO</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            @if ($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="image-preview"
                                    alt="{{ $post->title }}">
                            @else
                                <div
                                    style="width: 80px; height: 50px; background: hsl(var(--muted)); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: hsl(var(--muted-foreground));"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            @if($post->is_featured)
                                <span class="status-badge featured-badge" style="margin-left: 0.5rem;">
                                    <i class="fas fa-star" style="margin-right: 0.25rem; font-size: 0.625rem;"></i> Featured
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($post->category)
                                <span class="category-badge">{{ $post->category->name }}</span>
                            @else
                                <span style="color: hsl(var(--muted-foreground)); font-size: 0.8rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <button wire:click="toggleStatus({{ $post->id }})"
                                class="status-badge {{ $post->status === 'published' ? 'status-published' : 'status-draft' }}"
                                style="cursor: pointer; border: none;">
                                {{ ucfirst($post->status) }}
                            </button>
                        </td>
                        <td>
                            <span class="views-count"><i class="fas fa-eye"></i>
                                {{ number_format($post->views_count) }}</span>
                        </td>
                        <td>
                            @if($post->meta_title)<i class="fas fa-check-circle" style="color: rgb(21,128,61);"></i>@else<i
                            class="fas fa-times-circle" style="color: rgb(185,28,28);"></i>@endif
                            @if($post->meta_description)<i class="fas fa-check-circle"
                            style="color: rgb(21,128,61);"></i>@else<i class="fas fa-times-circle"
                                style="color: rgb(185,28,28);"></i>@endif
                        </td>
                        <td style="font-size: 0.8rem; color: hsl(var(--muted-foreground));">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="action-btns">
                                @if($post->status === 'published')
                                    <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" class="btn-icon" title="View">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                                <button wire:click="toggleFeatured({{ $post->id }})"
                                    class="btn-icon {{ $post->is_featured ? 'featured' : '' }}"
                                    title="{{ $post->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button wire:click="edit({{ $post->id }})" class="btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $post->id }})" class="btn-icon danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-blog"></i>
                                <p>No blog posts found. Create your first post!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">{{ $posts->links() }}</div>

    {{-- CREATE / EDIT MODAL --}}
    @if ($showForm)
        <div class="cms-modal-overlay" wire:click.self="cancel">
            <div class="cms-modal-container">
                <div class="cms-modal-header">
                    <div>
                        <h3 class="cms-modal-title">
                            <i class="fas fa-blog" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i>
                            {{ $editingId ? 'Edit Blog Post' : 'Create New Blog Post' }}
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
                                <label class="form-label">Post Title *</label>
                                <input type="text" class="form-input" wire:model.live="title"
                                    placeholder="Enter post title">
                                @error('title') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Slug *</label>
                                <input type="text" class="form-input" wire:model="slug" placeholder="post-slug">
                                @if($slug)
                                <div class="slug-preview">URL: {{ url('/blog/' . $slug) }}</div>@endif
                                @error('slug') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Excerpt</label>
                            <textarea class="form-input" wire:model="excerpt" rows="3"
                                placeholder="Brief summary (shown in listings)" maxlength="500"></textarea>
                            @error('excerpt') <span
                            style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Post Content</label>
                            <textarea class="form-input" wire:model="body" rows="15"
                                placeholder="Write your blog post content (HTML supported)"
                                style="min-height: 300px; font-family: monospace; font-size: 0.8rem;"></textarea>
                            @error('body') <span
                            style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select class="form-input" wire:model="blog_category_id">
                                    <option value="">— No Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-input" wire:model="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-input" wire:model="published_at">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <input type="file" class="form-input" wire:model="featured_image" accept="image/*">
                                @if ($existing_featured_image)
                                    <div class="slug-preview" style="margin-top: 0.5rem;">
                                        Current: <img src="{{ asset('storage/' . $existing_featured_image) }}"
                                            class="image-preview" alt="Featured">
                                    </div>
                                @endif
                                @error('featured_image') <span
                                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-check-inline">
                                    <input type="checkbox" id="is_featured" wire:model="is_featured">
                                    <label for="is_featured" class="form-label" style="margin-bottom: 0;">Mark as Featured
                                        Post</label>
                                </div>
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
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Canonical URL</label>
                                    <input type="url" class="form-input" wire:model="canonical_url"
                                        placeholder="https://...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-input" wire:model="meta_description" rows="3"
                                    placeholder="SEO description (max 160 chars)" maxlength="500"></textarea>
                                <div class="slug-preview">{{ strlen($meta_description) }}/160 characters recommended</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-input" wire:model="schema_markup" rows="6"
                                    placeholder='{"@context": "https://schema.org", "@type": "BlogPosting", ...}'
                                    style="font-family: monospace; font-size: 0.8rem;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="cms-modal-footer">
                        <button type="button" class="btn btn-outline" wire:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save"></i> {{ $editingId ? 'Update Post' : 'Create Post' }}
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
                <h3><i class="fas fa-exclamation-triangle" style="color: hsl(var(--destructive));"></i> Delete Post</h3>
                <p>Are you sure you want to delete this blog post? It will be soft-deleted and can be restored.</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" wire:click="cancelDelete">Cancel</button>
                    <button class="btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        function hello() {
            console.log('hello')
        }

    </script>
</div>