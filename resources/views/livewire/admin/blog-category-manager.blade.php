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
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid hsl(var(--border));
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

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid hsl(var(--border));
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

        .post-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            border-radius: 9999px;
            background: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
            font-size: 0.75rem;
            font-weight: 600;
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
        }
    </style>


    @if (session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if ($showForm)
        <div class="cms-form-card">
            <div class="card-title">
                <i class="fas fa-folder" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i>
                {{ $editingId ? 'Edit Category' : 'Create New Category' }}
            </div>

            <form wire:submit.prevent="save">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category Name *</label>
                        <input type="text" class="form-input" wire:model.live="name" placeholder="Enter category name">
                        @error('name') <span
                        style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Slug *</label>
                        <input type="text" class="form-input" wire:model="slug" placeholder="category-slug">
                        @if($slug)
                        <div class="slug-preview">URL: {{ url('/blog/category/' . $slug) }}</div>@endif
                        @error('slug') <span
                        style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input" wire:model="description" rows="3"
                        placeholder="Brief category description"></textarea>
                    @error('description') <span
                    style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>

                <div class="seo-section">
                    <h4><i class="fas fa-search"></i> SEO Settings</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-input" wire:model="meta_title" placeholder="SEO title"
                                maxlength="255">
                            @error('meta_title') <span
                            style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-input" wire:model="meta_description" rows="2"
                                placeholder="SEO description" maxlength="500"></textarea>
                            @error('meta_description') <span
                            style="color: hsl(var(--destructive)); font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ $editingId ? 'Update Category' : 'Create Category' }}
                    </button>
                    <button type="button" class="btn btn-outline" wire:click="cancel">Cancel</button>
                </div>
            </form>
        </div>
    @else
        <div class="cms-header">
            <h2><i class="fas fa-folder" style="color: hsl(var(--primary)); margin-right: 0.5rem;"></i> Blog Categories</h2>
            <button class="btn btn-primary" wire:click="create">
                <i class="fas fa-plus"></i> New Category
            </button>
        </div>

        <div class="cms-filters">
            <input type="text" class="form-input" wire:model.live.debounce.300ms="search"
                placeholder="Search categories...">
        </div>

        <div class="cms-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>SEO</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code
                                    style="font-size: 0.8rem; background: hsl(var(--muted)); padding: 0.125rem 0.5rem; border-radius: 4px;">/blog/category/{{ $category->slug }}</code>
                            </td>
                            <td><span class="post-count-badge">{{ $category->posts_count }}</span></td>
                            <td>
                                @if($category->meta_title)
                                    <i class="fas fa-check-circle" style="color: rgb(21,128,61);" title="Meta title set"></i>
                                @else
                                    <i class="fas fa-times-circle" style="color: rgb(185,28,28);" title="No meta title"></i>
                                @endif
                                @if($category->meta_description)
                                    <i class="fas fa-check-circle" style="color: rgb(21,128,61);" title="Meta description set"></i>
                                @else
                                    <i class="fas fa-times-circle" style="color: rgb(185,28,28);" title="No meta description"></i>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button wire:click="edit({{ $category->id }})" class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $category->id }})" class="btn-icon danger"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-folder"></i>
                                    <p>No categories found. Create your first category!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">{{ $categories->links() }}</div>
    @endif

    @if ($showDeleteConfirm)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3><i class="fas fa-exclamation-triangle" style="color: hsl(var(--destructive));"></i> Delete Category</h3>
                <p>Are you sure you want to delete this category? This cannot be undone.</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" wire:click="cancelDelete">Cancel</button>
                    <button class="btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>