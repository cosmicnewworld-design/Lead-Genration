<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicLeadCaptureController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
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

Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'doLogin']);
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'show'])->name('public.capture.show');
Route::post('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'store'])->name('public.capture.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/resume', [SubscriptionController::class, 'resume'])->name('subscriptions.resume');
    Route::get('/subscriptions/portal', [SubscriptionController::class, 'portal'])->name('subscriptions.portal');
});

Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

require __DIR__.'/auth.php';
