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
        $locale = Session::get('locale');

        if (!$locale && auth()->check()) {
            $locale = auth()->user()->locale ?? 'en';
        }

        if (!$locale && tenant()) {
            $locale = tenant()->locale ?? 'en';
        }

        $locale = in_array($locale, ['en', 'ur']) ? $locale : 'en';

        App::setLocale($locale);

        return $next($request);
    }
}