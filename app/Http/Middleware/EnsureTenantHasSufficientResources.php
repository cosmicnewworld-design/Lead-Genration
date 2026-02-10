<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTenantHasSufficientResources
{
    public function handle(Request $request, Closure $next, string $resource)
    {
        $tenant = auth()->user()->tenant;
        $plan = $tenant->subscription('default') ?
            \App\Models\Plan::where('stripe_price_id', $tenant->subscription('default')->stripe_price)->first() :
            null;

        if (!$plan) {
            // No plan, deny access
            return redirect()->route('billing.index')->with('error', 'You must be subscribed to a plan.');
        }

        $limitReached = false;
        switch ($resource) {
            case 'leads':
                if ($tenant->leads()->count() >= $plan->max_leads) {
                    $limitReached = true;
                }
                break;
            case 'campaigns':
                // Assuming you have a Campaign model
                // if ($tenant->campaigns()->count() >= $plan->max_campaigns) {
                //     $limitReached = true;
                // }
                break;
            case 'users':
                if ($tenant->users()->count() >= $plan->max_team_members) {
                    $limitReached = true;
                }
                break;
        }

        if ($limitReached) {
            return redirect()->route('billing.index')->with('error', "You have reached the limit for {$resource}. Please upgrade your plan.");
        }

        return $next($request);
    }
}
