<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next, ...$plans)
    {
        $team = $request->user()->currentTeam;

        if (!$team) {
            return redirect()->route('teams.create')->with('error', 'You need to create or join a team first.');
        }

        // If no specific plan is required, just check if subscription is active
        if (empty($plans)) {
            if (!$team->hasActiveSubscription()) {
                return redirect()->route('subscription.show')->with('error', 'Please subscribe to continue.');
            }
        } else {
            // Check if the team is subscribed to one of the required plans
            if (!$team->hasActiveSubscription() || !in_array($team->subscription_plan, $plans)) {
                return redirect()->route('subscription.show')
                    ->with('error', 'This feature requires a specific subscription plan.');
            }
        }

        return $next($request);
    }
}