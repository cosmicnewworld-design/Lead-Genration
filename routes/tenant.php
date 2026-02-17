<?php

declare(strict_types=1);

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CampaignAutomationController;
use App\Http\Controllers\CampaignStepController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ScoringRuleController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Settings\LeadSourceCategoryController;
use App\Http\Controllers\Settings\LeadSourceController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('leads', LeadsController::class);
    Route::get('leads-import', [LeadsController::class, 'import'])->name('leads.import');
    Route::post('leads-import', [LeadsController::class, 'importStore'])->name('leads.import.store');
    Route::get('segments/{segment}', [SegmentController::class, 'show'])->name('segments.show');

    Route::resource('campaigns', CampaignsController::class);
    Route::resource('campaigns.steps', CampaignStepController::class)->shallow();

    Route::resource('scoring-rules', ScoringRuleController::class);

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::resource('lead-source-categories', LeadSourceCategoryController::class)->except(['show']);
        Route::resource('lead-sources', LeadSourceController::class)->except(['show']);
    });

    Route::get('/scraper', function () {
        return view('scraper');
    })->name('scraper');
    Route::post('/scrape', [ScraperController::class, 'scrape'])->name('scrape.post');

    Route::post('/campaigns/{campaign}/start', [CampaignAutomationController::class, 'start'])->name('campaigns.start');

    Route::get('analytics/lead-score-distribution', [AnalyticsController::class, 'showLeadScoreDistribution'])->name('analytics.lead-score-distribution');

    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
});

Route::middleware(['auth', 'verified'])->prefix('billing')->as('subscriptions.')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('index');
    Route::post('/', [SubscriptionController::class, 'store'])->name('store');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
});