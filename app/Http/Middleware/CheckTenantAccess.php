<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantAccess
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $user = auth()->user();

    // 1. If not logged in, let the 'auth' middleware handle it
    if (!$user) {
      return $next($request);
    }

    // 2. Allow Super Admins universal access
    if ($user->isSuperAdmin()) {
      return $next($request);
    }

    // 3. Verify the user belongs to the current tenant being initialized
    // Note: 'tenant()' helper returns the current tenant object from Stancl/Tenancy
    $currentTenantId = tenant('id');

    if ($user->tenant_id !== $currentTenantId) {
      abort(403, 'Unauthorized access to this company.');
    }

    return $next($request);
  }
}
