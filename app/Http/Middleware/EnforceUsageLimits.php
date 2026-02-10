<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceUsageLimits
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $tenant = Auth::user()->tenant;
        $plan = $tenant->plan;

        if (!$plan) {
            // Or handle as a severe error
            return redirect()->route('subscriptions.index')->with('error', 'No active subscription plan found.');
        }

        $limitReached = false;
        switch ($feature) {
            case 'leads':
                if ($tenant->leads()->count() >= $plan->max_leads) {
                    $limitReached = true;
                }
                break;
            case 'campaigns':
                // Assuming you have a Campaign model related to the tenant
                if ($tenant->campaigns()->count() >= $plan->max_campaigns) {
                    $limitReached = true;
                }
                break;
            case 'users':
                if ($tenant->users()->count() >= $plan->max_team_members) {
                    $limitReached = true;
                }
                break;
        }

        if ($limitReached) {
            // You can flash a message and redirect
            return redirect()->route('dashboard')->with('error', "You have exceeded the limit for {$feature}. Please upgrade your plan.");
        }

        return $next($request);
    }
}
