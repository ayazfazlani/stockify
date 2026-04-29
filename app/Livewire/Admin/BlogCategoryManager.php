<?php

namespace App\Livewire\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class BlogCategoryManager extends Component
{
    use WithPagination;

    public string $search = '';

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $meta_title = '';
    public string $meta_description = '';

    // Confirmation
    public bool $showDeleteConfirm = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $slugRule = 'required|string|max:255|unique:blog_categories,slug';
        if ($this->editingId) {
            $slugRule .= ',' . $this->editingId;
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'description' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    public function updatedName($value)
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
        $category = BlogCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->meta_title = $category->meta_title ?? '';
        $this->meta_description = $category->meta_description ?? '';
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description ?: null,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ];

        if ($this->editingId) {
            BlogCategory::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Category updated successfully.');
        } else {
            BlogCategory::create($data);
            session()->flash('success', 'Category created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function confirmDelete(int $id)
    {
        $category = BlogCategory::withCount('posts')->findOrFail($id);
        if ($category->posts_count > 0) {
            session()->flash('error', "Cannot delete category with {$category->posts_count} posts. Reassign or delete posts first.");
            return;
        }
        $this->deletingId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $category = BlogCategory::withCount('posts')->findOrFail($this->deletingId);
            if ($category->posts_count === 0) {
                $category->delete();
                session()->flash('success', 'Category deleted successfully.');
            }
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

    private function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.blog-category-manager', compact('categories'));
    }
}
