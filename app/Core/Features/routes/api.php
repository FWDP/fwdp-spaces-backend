<?php

use App\Core\Features\Http\Controllers\FeatureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/features')->group(function () {
        Route::get('/', [FeatureController::class, 'index']);
        Route::post('/', [FeatureController::class, 'store']);
        Route::put('/{feature}', [FeatureController::class, 'update']);
        Route::delete('/{feature}', [FeatureController::class, 'destroy']);
    });
});