<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicLeadCaptureController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ScoringRuleController;
use App\Http\Controllers\AutomationRuleController;
use App\Http\Controllers\ConnectedEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController; // Import LeadController
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect()->route('login');
});

// New Tenant and User Registration Routes
Route::get('register', [TenantRegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('register', [TenantRegistrationController::class, 'store'])
    ->middleware('guest');

// Copied from auth.php to keep login/logout functionality
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Password Reset Routes
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


// --- All other existing routes from the original file ---

Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'doLogin']);
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::get('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'show'])->name('public.capture.show');
Route::post('/capture/{tenant_slug}', [PublicLeadCaptureController::class, 'store'])->name('public.capture.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Scoring Rules Resource Route
    Route::resource('scoring-rules', ScoringRuleController::class);

    // Automation Rules Resource Route
    Route::resource('automation-rules', AutomationRuleController::class);

    // Connected Emails Routes
    Route::get('/connected-emails', [ConnectedEmailController::class, 'index'])->name('connected-emails.index');
    Route::get('/connected-emails/connect', [ConnectedEmailController::class, 'connect'])->name('connected-emails.connect');
    Route::get('/connected-emails/callback', [ConnectedEmailController::class, 'callback'])->name('connected-emails.callback');
    Route::delete('/connected-emails/{connectedEmail}', [ConnectedEmailController::class, 'destroy'])->name('connected-emails.destroy');

    // Leads Resource Route
    Route::resource('leads', LeadController::class);

    // Lead Activities
    Route::post('leads/{lead}/activities', [ActivityController::class, 'store'])->name('leads.activities.store');

    // Lead Attachments
    Route::post('leads/{lead}/attachments', [AttachmentController::class, 'store'])->name('leads.attachments.store');
    Route::get('leads/{lead}/attachments/{attachment}', [AttachmentController::class, 'download'])->name('leads.attachments.download');
    Route::delete('leads/{lead}/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('leads.attachments.destroy');

    // Billing and Subscription Routes
    Route::get('/billing', [SubscriptionController::class, 'index'])->name('billing.index'); // Use SubscriptionController
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('billing.checkout');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/resume', [SubscriptionController::class, 'resume'])->name('subscriptions.resume');
    Route::get('/subscriptions/portal', [SubscriptionController::class, 'portal'])->name('subscriptions.portal');
});

Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');


// Role-based access control routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'showAdminDashboard'])
        ->middleware('role:admin')->name('admin.dashboard.test');

    Route::get('/management-dashboard', [DashboardController::class, 'showManagerDashboard'])
        ->middleware('role:admin,manager')->name('management.dashboard.test');

    Route::get('/sales-dashboard', [DashboardController::class, 'showSalesDashboard'])
        ->middleware('role:admin,manager,sales_rep')->name('sales.dashboard.test');
});

Route::get('/unauthorized', [DashboardController::class, 'showUnauthorized'])->name('unauthorized');
