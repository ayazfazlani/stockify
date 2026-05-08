<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Update the application locale.
     */
    public function update(Request $request, string $lang, ?string $tenant = null)
    {
        if (! in_array($lang, ['en', 'ur'])) {
            abort(400);
        }

        // 1. Persist to Session
        Session::put('locale', $lang);
        Session::save();

        // 2. Persist to Cookie (for reliability across different initialization states)
        cookie()->queue('locale', $lang, 60 * 24 * 30); // 30 days

        // 3. Persist to User Profile if authenticated
        if (auth()->check()) {
            auth()->user()->update(['locale' => $lang]);
        }

        // 4. Persist to Tenant Settings if in tenant context
        if (function_exists('tenant') && tenant()) {
            tenant()->update(['locale' => $lang]);
        }

        // Apply immediately for current request context
        App::setLocale($lang);

        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $query = parse_url($previousUrl, PHP_URL_QUERY);

        $path = ltrim((string) $path, '/');
        $segments = explode('/', $path);
        if (empty($segments[0])) {
            $segments = [''];
        }
        $supportedLocales = ['en', 'ur'];

        $nonLocalizedPrefixes = ['super-admin', 'api', 'stripe', 'pwa', 'up', 'build'];
        $publicPaths = ['', 'marketplace', 'blog', 'login', 'register', 'invite', 'find-store', 'tenant-register', 'forgot-password', 'reset-password'];

        $isPublic = in_array($segments[0], $publicPaths);
        $isCms = false;

        if (! $isPublic && ! in_array($segments[0], $supportedLocales) && ! in_array($segments[0], $nonLocalizedPrefixes)) {
            $isCms = CmsPage::where('slug', $segments[0])->exists();
        }

        if (in_array($segments[0], $supportedLocales)) {
            // Already localized, just swap
            $segments[0] = $lang;
            $newPath = implode('/', $segments);
        } elseif ($isPublic || $isCms) {
            // Public page or CMS page - prepend locale
            $newPath = $lang.($path ? '/'.$path : '');
        } else {
            // Private, tenant, or system path - don't prepend locale
            // (Language will still change in session for these)
            $newPath = $path;
        }

        $finalUrl = '/'.$newPath.($query ? '?'.$query : '');

        return redirect($finalUrl);
    }
}
