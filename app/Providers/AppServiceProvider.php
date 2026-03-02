<?php

namespace App\Providers;

use App\Models\Lead;
use App\Models\ScoringRule;
use App\Observers\LeadObserver;
use App\Observers\ScoringRuleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ScoringRule::observe(ScoringRuleObserver::class);
        Lead::observe(LeadObserver::class);
    }
}
