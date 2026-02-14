<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadsController; // Changed from LeadController
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicLeadCaptureController;
use App\Http\Controllers\CampaignAutomationController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\CampaignStepController;
use App\Http\Controllers\ScoringRuleController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SegmentController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome', [
        'tenants' => Tenant::with('users')->get(),
    ]);
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is successful!';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Using the new LeadsController
    Route::resource('leads', LeadsController::class);
    Route::get('segments/{segment}', [SegmentController::class, 'show'])->name('segments.show');

    Route::resource('campaigns', CampaignsController::class);
    Route::resource('campaigns.steps', CampaignStepController::class)->shallow();

    Route::resource('scoring-rules', ScoringRuleController::class);

    Route::get('/scraper', function () {
        return view('scraper');
    })->name('scraper');
    Route::post('/scrape', [ScraperController::class, 'scrape'])->name('scrape.post');

    // Campaign Automation
    Route::post('/campaigns/{campaign}/start', [CampaignAutomationController::class, 'start'])->name('campaigns.start');

    // Analytics
    Route::get('analytics/lead-score-distribution', [AnalyticsController::class, 'showLeadScoreDistribution'])->name('analytics.lead-score-distribution');
});

// Admin Routes
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'doLogin']);
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Note: The admin destroy route for leads will need to be updated if you keep it
    // Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])->name('leads.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('billing')->as('subscriptions.')->group(function () {
    Route::get('/', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\SubscriptionController::class, 'store'])->name('store');
    Route::post('/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('cancel');
});

// Public Lead Capture
Route::get('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'show'])->name('public.capture.show');
Route::post('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'store'])->name('public.capture.store');

require __DIR__.'/auth.php';
