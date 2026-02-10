<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\User;

Route::get('/', function () {
    return view('welcome', [
        'tenants' => Tenant::with('users')->get(),
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Replaced individual lead routes with a resourceful route
    Route::resource('leads', LeadController::class);

    Route::get('/scraper', function () {
        return view('scraper');
    })->name('scraper');
    Route::post('/scrape', [ScraperController::class, 'scrape'])->name('scrape.post');
});

// Admin Routes
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'doLogin']);
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])->name('leads.destroy');
});

require __DIR__.'/auth.php';
