<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'meta_title',
        'meta_description',
        'canonical_url',
        'schema_markup',
        'og_image',
        'status',
        'is_system',
        'sort_order',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_system' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }

    public function getEffectiveMetaTitleAttribute(): string
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }

        $template = SeoSetting::getValue('default_meta_title', '{{page_title}} | StockFlow');
        return str_replace('{{page_title}}', $this->title, $template);
    }
}
