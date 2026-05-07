<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check Session
        $locale = Session::get('locale');

        // 2. Check Cookie
        if (! $locale) {
            $locale = $request->cookie('locale');
        }

        // 3. Check Authenticated User
        if (! $locale && auth()->check()) {
            $locale = auth()->user()->locale;
        }

        // 4. Check Tenant
        if (! $locale && function_exists('tenant') && tenant()) {
            $locale = tenant()->locale;
        }

        // Default or Fallback
        $locale = in_array($locale, ['en', 'ur']) ? $locale : config('app.locale', 'en');

        App::setLocale($locale);

        return $next($request);
    }
}
