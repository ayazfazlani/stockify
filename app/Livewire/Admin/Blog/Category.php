<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogCategory as BlogCategoryModel;
use App\Models\SeoSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class Category extends Component
{
    use WithPagination;

    public $slug;
    public $category;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->category = BlogCategoryModel::where('slug', $this->slug)->firstOrFail();
    }


    #[Layout('components.layouts.web')]
    public function render()
    {
        $posts = BlogCategoryModel::find($this->category->id)
            ->posts()
            ->published()
            ->with('category')
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategoryModel::withCount('publishedPosts')
            ->having('published_posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $globalSchema = SeoSetting::getGlobalSchema();

        return view('livewire.admin.blog.category', compact('posts', 'categories', 'globalSchema'));
    }
}
