<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'cms:generate-sitemap';

    protected $description = 'Regenerate and cache the XML sitemap';

    public function handle(): int
    {
        $this->info('Regenerating sitemap...');

        cache()->forget('sitemap_xml');

        // Reuse the controller's generation logic to avoid code duplication
        $controller = app(SitemapController::class);
        $response = $controller->index();
        $urlCount = substr_count($response->getContent(), '<url>');

        $this->info("Sitemap generated with {$urlCount} URLs.");

        return self::SUCCESS;
    }
}
