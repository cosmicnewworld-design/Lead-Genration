<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class TenantServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            if (Auth::check()) {
                $tenant = Auth::user()->tenant;
                if ($tenant) {
                    config(['tenant.id' => $tenant->id]);
                }
            }
        });
    }
}
