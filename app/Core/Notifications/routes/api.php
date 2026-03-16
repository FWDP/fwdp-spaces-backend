<?php

use App\Core\Notifications\Http\Controllers\NotificationController;
use App\Core\Notifications\Http\Controllers\AdminNotificationController;

Route::middleware('auth:api')->group(function () {
    Route::prefix('api/notifications')->group(function () {
        Route::get("/", [NotificationController::class, 'index']);
        Route::post("/{notification}/read", [NotificationController::class, 'markRead']);
    });
});

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/admin/notifications')->group(function () {
        Route::post("/broadcast", [AdminNotificationController::class, 'broadcast']);
    });
});