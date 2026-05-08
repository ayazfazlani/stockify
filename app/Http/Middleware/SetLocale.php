<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check URL Segment (Priority for SEO)
        $urlLocale = $request->segment(1);
        $supportedLocales = ['en', 'ur'];

        if (in_array($urlLocale, $supportedLocales)) {
            $locale = $urlLocale;
            Session::put('locale', $locale);
            cookie()->queue('locale', $locale, 60 * 24 * 30);
        } else {
            // 2. Check Session
            $locale = Session::get('locale');

            // 3. Check Cookie
            if (! $locale) {
                $locale = $request->cookie('locale');
            }

            // 4. Check Authenticated User
            if (! $locale && auth()->check()) {
                $locale = auth()->user()->locale;
            }

            // 5. Check Tenant
            if (! $locale && function_exists('tenant') && tenant()) {
                $locale = tenant()->locale;
            }
        }

        // Default or Fallback
        $locale = in_array($locale, $supportedLocales) ? $locale : config('app.locale', 'en');

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
