<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'blog_category_id',
        'meta_title',
        'meta_description',
        'canonical_url',
        'schema_markup',
        'og_image',
        'status',
        'is_featured',
        'views_count',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function () {
            cache()->forget('sitemap_xml');
        });

        static::deleted(function () {
            cache()->forget('sitemap_xml');
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUrlAttribute(): string
    {
        return url('/blog/' . $this->slug);
    }

    public function getEffectiveMetaTitleAttribute(): string
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }

        $template = SeoSetting::getValue('default_meta_title', '{{page_title}} | StockFlow');
        return str_replace('{{page_title}}', $this->title, $template);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->body ?? ''));
        return max(1, (int) ceil($words / 200));
    }
}
