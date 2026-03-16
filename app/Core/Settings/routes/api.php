<?php

use App\Core\Settings\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::get('/{key}', [SettingController::class, 'show']);
        Route::put('/{key}', [SettingController::class, 'update']);
    });
});
