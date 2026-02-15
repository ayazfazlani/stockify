<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->is_super_admin) {
            return redirect()->route('super-admin.login');
        }

        return $next($request);
    }
}