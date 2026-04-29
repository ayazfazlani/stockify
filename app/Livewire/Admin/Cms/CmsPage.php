<?php

namespace App\Livewire\Admin\Cms;

use App\Models\CmsPage as CmsPageModel;
use App\Models\SeoSetting;
use Livewire\Component;

class CmsPage extends Component
{
    public $slug;
    public $page;
    public $globalSchema;

    public function mount($slug)
    {
        dd($slug);
        $this->slug = $slug;
        $this->page = CmsPageModel::published()
            ->where('slug', $this->slug)
            ->firstOrFail();
        $this->globalSchema = SeoSetting::getGlobalSchema();
    }

    public function render()
    {
        return view('livewire.cms.cms-page', [
            'page' => $this->page,
            'globalSchema' => $this->globalSchema,
        ]);
    }
}