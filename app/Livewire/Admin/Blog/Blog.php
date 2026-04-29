<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\SeoSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    #[Layout('components.layouts.web')]
    public function render()
    {
        $featuredPosts = BlogPost::published()
            ->featured()
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        $posts = BlogPost::published()
            ->with('category')
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::withCount('publishedPosts')
            ->having('published_posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $seoDescription = SeoSetting::getValue('site_description', 'Read the latest articles and updates.');
        $globalSchema = SeoSetting::getGlobalSchema();

        return view('livewire.admin.blog.index', compact('featuredPosts', 'posts', 'categories', 'seoDescription', 'globalSchema'));
    }
}
