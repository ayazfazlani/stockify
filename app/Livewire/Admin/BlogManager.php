<?php

namespace App\Livewire\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BlogManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterCategory = '';

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $body = '';
    public $featured_image;
    public string $existing_featured_image = '';
    public ?int $blog_category_id = null;
    public string $meta_title = '';
    public string $meta_description = '';
    public string $canonical_url = '';
    public string $schema_markup = '';
    public string $status = 'draft';
    public bool $is_featured = false;
    public ?string $published_at = null;

    // Confirmation
    public bool $showDeleteConfirm = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $slugRule = 'required|string|max:255|unique:blog_posts,slug';
        if ($this->editingId) {
            $slugRule .= ',' . $this->editingId;
        }

        return [
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'excerpt' => 'nullable|string|max:500',
            'body' => 'nullable|string',
            'featured_image' => $this->editingId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'schema_markup' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->editingId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt ?? '';
        $this->body = $post->body ?? '';
        $this->existing_featured_image = $post->featured_image ?? '';
        $this->blog_category_id = $post->blog_category_id;
        $this->meta_title = $post->meta_title ?? '';
        $this->meta_description = $post->meta_description ?? '';
        $this->canonical_url = $post->canonical_url ?? '';
        $this->schema_markup = $post->schema_markup ? json_encode($post->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '';
        $this->status = $post->status;
        $this->is_featured = $post->is_featured;
        $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : null;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body,
            'blog_category_id' => $this->blog_category_id ?: null,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'canonical_url' => $this->canonical_url ?: null,
            'schema_markup' => $this->schema_markup ? json_decode($this->schema_markup, true) : null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at ?: ($this->status === 'published' ? now() : null),
        ];

        if ($this->featured_image && !is_string($this->featured_image)) {
            $data['featured_image'] = $this->featured_image->store('cms/blog', 'public');
        }

        if ($this->editingId) {
            $post = BlogPost::findOrFail($this->editingId);
            $post->update($data);
            session()->flash('success', 'Blog post updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            BlogPost::create($data);
            session()->flash('success', 'Blog post created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function confirmDelete(int $id)
    {
        $this->deletingId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            BlogPost::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Blog post deleted successfully.');
        }
        $this->showDeleteConfirm = false;
        $this->deletingId = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->deletingId = null;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleStatus(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'status' => $post->status === 'published' ? 'draft' : 'published',
            'published_at' => $post->status === 'draft' ? now() : $post->published_at,
        ]);
        session()->flash('success', 'Post status updated.');
    }

    public function toggleFeatured(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->update(['is_featured' => !$post->is_featured]);
        session()->flash('success', 'Featured status updated.');
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->body = '';
        $this->featured_image = null;
        $this->existing_featured_image = '';
        $this->blog_category_id = null;
        $this->meta_title = '';
        $this->meta_description = '';
        $this->canonical_url = '';
        $this->schema_markup = '';
        $this->status = 'draft';
        $this->is_featured = false;
        $this->published_at = null;
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $posts = BlogPost::query()
            ->with('category')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterCategory, fn($q) => $q->where('blog_category_id', $this->filterCategory))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = BlogCategory::orderBy('name')->get();

        return view('livewire.admin.blog-manager', compact('posts', 'categories'));
    }
}
