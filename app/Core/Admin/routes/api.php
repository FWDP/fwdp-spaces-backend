<?php

Route::prefix('api')->group(function () {
    Route::prefix('admin')->middleware(['auth:api, role:ADMIN'])->group(function () {
        Route::get("/dashboard", [\App\Core\Admin\Http\Controllers\AdminDashboardController::class, "index"]);
    });
});
