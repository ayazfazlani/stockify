<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogPost;
use App\Models\SeoSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $slug;
    public $post;
    public $relatedPosts;
    public $globalSchema;

    public function mount($slug)
    {
        
        $this->slug = $slug;
        $this->loadPost();
    }

    public function loadPost()
    {
        $this->post = BlogPost::published()
            ->where('slug', $this->slug)
            ->with('category', 'creator')
            ->firstOrFail();

        $this->post->incrementViews();

        $this->relatedPosts = BlogPost::published()
            ->where('id', '!=', $this->post->id)
            ->when($this->post->blog_category_id, fn($q) => $q->where('blog_category_id', $this->post->blog_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        $this->globalSchema = SeoSetting::getGlobalSchema();
    }

    #[Layout('components.layouts.web')]
    public function render()
    {
        return view('livewire.admin.blog.show', [
            'post' => $this->post,
            'relatedPosts' => $this->relatedPosts,
            'globalSchema' => $this->globalSchema,
        ]);
    }
}
