<?php

use App\Core\Admin\Http\Controllers\AdminDashboardController;

Route::prefix('api')->group(function () {
    Route::prefix('admin')->middleware(['auth:api', 'role:ADMIN'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    });
});
