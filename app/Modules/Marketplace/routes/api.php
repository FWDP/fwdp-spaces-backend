<?php

use App\Modules\Marketplace\Http\Controllers\MarketplaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/marketplace')->middleware('auth:api')->group(function () {
    Route::get('modules', [MarketplaceController::class, 'index']);
    Route::post('modules/{module}/install', [MarketplaceController::class, 'install']);
    Route::delete('modules/{module}/uninstall', [MarketplaceController::class, 'uninstall']);
    Route::patch('modules/{module}/toggle', [MarketplaceController::class, 'toggle']);
});
