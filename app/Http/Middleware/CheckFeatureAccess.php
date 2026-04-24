<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     *
     * Check if the current tenant's plan has the required feature(s).
     *
     * Usage in routes:
     *   ->middleware('feature:analytics')
     *   ->middleware('feature:analytics,advanced-reports')
     */
    public function handle(Request $request, Closure $next, string ...$features): Response
    {
        $tenant = tenant();

        if (! $tenant) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'No active tenant.',
                ], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Please log in to access this feature.');
        }

        // Super admin bypass — they can access everything
        if ($request->user()?->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($features as $feature) {
            if (! $tenant->hasFeature($feature)) {
                $upgradeUrl = $this->upgradePlansUrl($request);

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'This feature is not available on your current plan.',
                        'feature' => $feature,
                        'upgrade_url' => $upgradeUrl,
                    ], 403);
                }

                return redirect()->to($upgradeUrl)
                    ->with('error', 'This feature requires a plan upgrade.');
            }
        }

        return $next($request);
    }

    protected function upgradePlansUrl(Request $request): string
    {
        if (function_exists('tenancy') && tenancy()->initialized && tenant('slug')) {
            return route('tenant.plans.index', ['tenant' => tenant('slug')]);
        }

        if (Route::has('subscription.index')) {
            return route('subscription.index');
        }

        return url('/');
    }
}