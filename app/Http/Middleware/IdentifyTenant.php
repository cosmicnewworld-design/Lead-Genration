<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('tenant_slug')) {
            $tenant = Tenant::where('slug', $request->route('tenant_slug'))->firstOrFail();
            app()->instance('tenant', $tenant);
        } elseif (Auth::check() && Auth::user()->tenant) {
            $tenant = Auth::user()->tenant;
            app()->instance('tenant', $tenant);
        }

        return $next($request);
    }
}
