<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Update the application locale.
     */
    public function update(Request $request, string $locale, ?string $tenant = null)
    {
        if (! in_array($locale, ['en', 'ur'])) {
            abort(400);
        }

        // 1. Persist to Session
        Session::put('locale', $locale);
        Session::save();

        // 2. Persist to Cookie (for reliability across different initialization states)
        cookie()->queue('locale', $locale, 60 * 24 * 30); // 30 days

        // 3. Persist to User Profile if authenticated
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        // 4. Persist to Tenant Settings if in tenant context
        if (function_exists('tenant') && tenant()) {
            tenant()->update(['locale' => $locale]);
        }

        // Apply immediately for current request context
        App::setLocale($locale);

        return redirect()->back();
    }
}
