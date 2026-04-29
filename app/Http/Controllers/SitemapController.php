<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\SeoSetting;
use Illuminate\Http\Response;

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
        $baseUrl = config('app.url');

        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => $baseUrl,
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        // Blog index
        $urls[] = [
            'loc' => $baseUrl . '/blog',
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        // CMS Pages
        $pages = CmsPage::published()->get();
        foreach ($pages as $page) {
            $urls[] = [
                'loc' => $baseUrl . '/' . $page->slug,
                'lastmod' => $page->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Blog Posts
        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $baseUrl . '/blog/' . $post->slug,
                'lastmod' => $post->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        // Blog Categories
        $categories = \App\Models\BlogCategory::has('posts')->get();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => $baseUrl . '/blog/category/' . $category->slug,
                'lastmod' => $category->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
        }

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
