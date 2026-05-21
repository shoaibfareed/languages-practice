<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/translations/search', [TranslationController::class, 'search']);
    Route::get('/translations/export', [TranslationController::class, 'export']);

    Route::apiResource('translations', TranslationController::class);
});
