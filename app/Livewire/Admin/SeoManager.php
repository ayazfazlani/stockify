<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\SeoSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SeoManager extends Component
{
    public string $activeTab = 'robots';

    // Robots.txt
    public string $robots_txt = '';

    // Global SEO
    public string $default_meta_title = '';
    public string $site_description = '';
    public string $global_schema = '';

    // Sitemap info
    public int $publishedPages = 0;
    public int $publishedPosts = 0;
    public ?string $lastGenerated = null;

    public function mount()
    {
        $this->robots_txt = SeoSetting::getRobotsTxt();
        $this->default_meta_title = SeoSetting::getValue('default_meta_title', '{{page_title}} | StockFlow');
        $this->site_description = SeoSetting::getValue('site_description', '');
        $globalSchema = SeoSetting::getValue('global_schema', '{}');
        $this->global_schema = json_encode(json_decode($globalSchema, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}';

        $this->refreshSitemapStats();
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function saveRobotsTxt()
    {
        $this->validate(['robots_txt' => 'required|string']);

        SeoSetting::setValue('robots_txt', $this->robots_txt);
        session()->flash('success', 'Robots.txt updated successfully.');
    }

    public function saveGlobalSeo()
    {
        $this->validate([
            'default_meta_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'global_schema' => 'nullable|string',
        ]);

        SeoSetting::setValue('default_meta_title', $this->default_meta_title);
        SeoSetting::setValue('site_description', $this->site_description);

        if ($this->global_schema) {
            $decoded = json_decode($this->global_schema, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('global_schema', 'Invalid JSON format.');
                return;
            }
            SeoSetting::setValue('global_schema', json_encode($decoded));
        }

        session()->flash('success', 'Global SEO settings saved successfully.');
    }

    public function regenerateSitemap()
    {
        cache()->forget('sitemap_xml');
        $this->refreshSitemapStats();
        session()->flash('success', 'Sitemap cache cleared. It will be regenerated on next request.');
    }

    private function refreshSitemapStats()
    {
        $this->publishedPages = CmsPage::published()->count();
        $this->publishedPosts = BlogPost::published()->count();

        // Check if sitemap is cached
        $this->lastGenerated = cache()->has('sitemap_xml')
            ? 'Cached (active)'
            : 'Not cached (will regenerate on next request)';
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.seo-manager');
    }
}
