<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Api\V1\LeadController as ApiLeadController;
use App\Http\Controllers\Api\V1\WebhookController as ApiWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::prefix('v1')->group(function () {
    // Webhook endpoints (require tenant authentication)
    Route::post('webhooks/{tenant}', [ApiWebhookController::class, 'handle'])
        ->name('api.webhooks.handle');
});

// Authenticated API routes
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('leads', ApiLeadController::class);
    
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });
});

// Legacy routes (for backward compatibility)
Route::apiResource('leads', LeadController::class);
Route::get('analytics/lead-score-distribution', [AnalyticsController::class, 'leadScoreDistribution']);
