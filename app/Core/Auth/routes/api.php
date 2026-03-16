<?php

use App\Core\Auth\Http\Controllers\AuthController;

Route::prefix('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post("/callback", [AuthController::class, 'callback']);
    });
});
