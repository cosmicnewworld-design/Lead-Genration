<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('leads', LeadController::class);
    Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');

    Route::resource("businesses", BusinessController::class);
    Route::get('businesses/{business}/outreach', [BusinessController::class, 'outreach'])->name('businesses.outreach');
    Route::post('businesses/{business}/outreach/send', [BusinessController::class, 'sendOutreach'])->name('businesses.outreach.send');
});

// Publicly accessible routes
Route::get('/scrape', [ScraperController::class, 'scrape']);
Route::post('/analyze', [AiController::class, 'analyze']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsApp']);

require __DIR__.'/auth.php';
