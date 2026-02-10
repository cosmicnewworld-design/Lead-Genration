<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UsageLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant();

        if ($tenant) {
            $usageCount = $tenant->leads()->count(); // Or any other resource you want to limit

            if ($usageCount >= $tenant->usage_limit) {
                // You could redirect, show an error, or return a JSON response
                return response()->json(['error' => 'Usage limit exceeded.'], 403);
            }
        }

        return $next($request);
    }
}
