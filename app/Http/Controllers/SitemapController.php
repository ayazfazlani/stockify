<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Item;
use App\Models\Store;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $content = cache()->remember('sitemap_xml', 60 * 60 * 24, function () {
            return $this->generateSitemap();
        });

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    protected function generateSitemap(): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $urls = [];

        // ── 1. Static / Site Pages ──────────────────────────────────
        $urls[] = $this->url($baseUrl, now(), 'daily', '1.0');

        // Marketplace hub pages
        $urls[] = $this->url($baseUrl.'/marketplace', now(), 'daily', '0.9');
        $urls[] = $this->url($baseUrl.'/marketplace/stores', now(), 'daily', '0.8');
        $urls[] = $this->url($baseUrl.'/marketplace/search', now(), 'weekly', '0.6');

        // Blog index
        $urls[] = $this->url($baseUrl.'/blog', now(), 'daily', '0.8');

        // ── 2. CMS Pages ────────────────────────────────────────────
        CmsPage::published()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->chunk(500, function ($pages) use (&$urls, $baseUrl) {
                foreach ($pages as $page) {
                    $urls[] = $this->url(
                        $baseUrl.'/'.$page->slug,
                        $page->updated_at,
                        'weekly',
                        '0.7'
                    );
                }
            });

        // ── 3. Blog Posts ───────────────────────────────────────────
        BlogPost::published()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->chunk(500, function ($posts) use (&$urls, $baseUrl) {
                foreach ($posts as $post) {
                    $urls[] = $this->url(
                        $baseUrl.'/blog/'.$post->slug,
                        $post->updated_at,
                        'weekly',
                        '0.6'
                    );
                }
            });

        // ── 4. Blog Categories ──────────────────────────────────────
        BlogCategory::has('posts')
            ->select('slug', 'updated_at')
            ->get()
            ->each(function ($category) use (&$urls, $baseUrl) {
                $urls[] = $this->url(
                    $baseUrl.'/blog/category/'.$category->slug,
                    $category->updated_at,
                    'weekly',
                    '0.5'
                );
            });

        // ── 5. Marketplace Stores (public) ──────────────────────────
        Store::where('is_public', true)
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->chunk(500, function ($stores) use (&$urls, $baseUrl) {
                foreach ($stores as $store) {
                    $urls[] = $this->url(
                        $baseUrl.'/marketplace/store/'.$store->slug,
                        $store->updated_at,
                        'weekly',
                        '0.7'
                    );
                }
            });

        // ── 6. Marketplace Products (public items from public stores)
        Item::public()
            ->select('items.slug', 'items.updated_at')
            ->orderBy('items.updated_at', 'desc')
            ->chunk(500, function ($items) use (&$urls, $baseUrl) {
                foreach ($items as $item) {
                    $urls[] = $this->url(
                        $baseUrl.'/marketplace/product/'.$item->slug,
                        $item->updated_at ?? $item->created_at ?? now(),
                        'weekly',
                        '0.6'
                    );
                }
            });

        // ── 7. Marketplace Categories (with public items) ───────────
        Category::where('is_active', true)
            ->whereHas('items', function ($q) {
                $q->public();
            })
            ->select('slug', 'updated_at')
            ->get()
            ->each(function ($category) use (&$urls, $baseUrl) {
                $urls[] = $this->url(
                    $baseUrl.'/marketplace/category/'.$category->slug,
                    $category->updated_at,
                    'weekly',
                    '0.5'
                );
            });

        // ── Build XML ───────────────────────────────────────────────
        return $this->buildXml($urls);
    }

    /**
     * Build a single URL entry array.
     *
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string}
     */
    protected function url(string $loc, mixed $lastmod, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod instanceof Carbon
                ? $lastmod->toAtomString()
                : (string) $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * Build the XML string from the URL array.
     *
     * @param  array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>  $urls
     */
    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.htmlspecialchars($url['loc']).'</loc>'."\n";
            $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'."\n";
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$url['priority'].'</priority>'."\n";
            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
