<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('leads', LeadController::class);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('analytics/lead-score-distribution', [AnalyticsController::class, 'leadScoreDistribution']);
