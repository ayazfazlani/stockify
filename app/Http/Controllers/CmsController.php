<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\SeoSetting;

class CmsController extends Controller
{
    /**
     * Blog index page.
     */
    public function blogIndex()
    {
        return view('blog.index');
    }

    /**
     * Single blog post.
     */
    public function blogShow(string $slug)
    {
        $post = \App\Models\BlogPost::with(['category', 'creator'])->where('slug', $slug)->firstOrFail();

        // Fetch related posts: same category, published, not the current post, limit 6
        $relatedPosts = collect();
        if ($post->category) {
            $relatedPosts = \App\Models\BlogPost::with('category')
                ->published()
                ->where('blog_category_id', $post->blog_category_id)
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->limit(6)
                ->get();
        }

        return view('livewire.admin.blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Blog category page.
     */
    public function blogCategory(string $slug)
    {
        return view('blog.category', compact('slug'));
    }

    /**
     * CMS dynamic page (catch-all).
     */
    public function cmsPage(string $slug)
    {
        $page = \App\Models\CmsPage::published()->where('slug', $slug)->firstOrFail();
        $globalSchema = \App\Models\SeoSetting::getValue('global_schema', null);
        return view('cms-page', compact('page', 'globalSchema'));
    }
}
