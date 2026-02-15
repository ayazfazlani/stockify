<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\FeatureService;
use Illuminate\Http\Request;

class CheckFeatureAccess
{
    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function handle(Request $request, Closure $next, string $feature)
    {
        $team = $request->user()->currentTeam;

        if (!$this->featureService->teamHasFeature($team, $feature)) {
            return redirect()->route('subscription.index')
                ->with('error', 'This feature requires a higher subscription plan.');
        }

        return $next($request);
    }
}