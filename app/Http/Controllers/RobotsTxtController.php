<?php

namespace App\Http\Controllers;

use App\Models\SeoSetting;
use Illuminate\Http\Response;

class RobotsTxtController extends Controller
{
    public function index(): Response
    {
        $content = SeoSetting::getRobotsTxt();

        // Always append sitemap URL if not already present
        if (stripos($content, 'sitemap:') === false) {
            $content .= "\n\nSitemap: " . config('app.url') . "/sitemap.xml";
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
