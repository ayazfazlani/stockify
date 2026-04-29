<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\CmsPage;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'cms:generate-sitemap';
    protected $description = 'Regenerate and cache the XML sitemap';

    public function handle(): int
    {
        $this->info('Regenerating sitemap...');

        // Clear existing cache
        cache()->forget('sitemap_xml');

        // Trigger regeneration by calling the sitemap controller logic
        $baseUrl = config('app.url');
        $urls = [];

        $urls[] = ['loc' => $baseUrl, 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => $baseUrl . '/blog', 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.8'];

        $pages = CmsPage::published()->get();
        foreach ($pages as $page) {
            $urls[] = ['loc' => $baseUrl . '/' . $page->slug, 'lastmod' => $page->updated_at->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.7'];
        }

        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $urls[] = ['loc' => $baseUrl . '/blog/' . $post->slug, 'lastmod' => $post->updated_at->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.6'];
        }

        $categories = \App\Models\BlogCategory::has('posts')->get();
        foreach ($categories as $category) {
            $urls[] = ['loc' => $baseUrl . '/blog/category/' . $category->slug, 'lastmod' => $category->updated_at->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.5'];
        }

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

        cache()->put('sitemap_xml', $xml, 60 * 60 * 24);

        $this->info("Sitemap generated with " . count($urls) . " URLs.");
        return self::SUCCESS;
    }
}
