<?php

namespace App\Livewire\Admin;

use App\Models\CmsPage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PageManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterStatus = '';

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $slug = '';
    public string $body = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $canonical_url = '';
    public string $schema_markup = '';
    public $og_image;
    public string $status = 'draft';
    public int $sort_order = 0;

    // Confirmation
    public bool $showDeleteConfirm = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $slugRule = 'required|string|max:255|unique:cms_pages,slug';
        if ($this->editingId) {
            $slugRule .= ',' . $this->editingId;
        }

        return [
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'body' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'schema_markup' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'sort_order' => 'integer|min:0',
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
        // dd('create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $page = CmsPage::findOrFail($id);
        $this->editingId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->body = $page->body ?? '';
        $this->meta_title = $page->meta_title ?? '';
        $this->meta_description = $page->meta_description ?? '';
        $this->canonical_url = $page->canonical_url ?? '';
        $this->schema_markup = $page->schema_markup ? json_encode($page->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '';
        $this->status = $page->status;
        $this->sort_order = $page->sort_order;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'canonical_url' => $this->canonical_url ?: null,
            'schema_markup' => $this->schema_markup ? json_decode($this->schema_markup, true) : null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->og_image && !is_string($this->og_image)) {
            $data['og_image'] = $this->og_image->store('cms/og-images', 'public');
        }

        if ($this->editingId) {
            $page = CmsPage::findOrFail($this->editingId);
            $page->update($data);
            session()->flash('success', 'Page updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            CmsPage::create($data);
            session()->flash('success', 'Page created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function confirmDelete(int $id)
    {
        $page = CmsPage::findOrFail($id);
        if ($page->is_system) {
            session()->flash('error', 'System pages cannot be deleted.');
            return;
        }
        $this->deletingId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $page = CmsPage::findOrFail($this->deletingId);
            if (!$page->is_system) {
                $page->delete();
                session()->flash('success', 'Page deleted successfully.');
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

    public function toggleStatus(int $id)
    {
        $page = CmsPage::findOrFail($id);
        $page->update([
            'status' => $page->status === 'published' ? 'draft' : 'published',
            'published_at' => $page->status === 'draft' ? now() : $page->published_at,
        ]);
        session()->flash('success', 'Page status updated.');
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->body = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->canonical_url = '';
        $this->schema_markup = '';
        $this->og_image = null;
        $this->status = 'draft';
        $this->sort_order = 0;
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $pages = CmsPage::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.page-manager', compact('pages'));
    }
}
