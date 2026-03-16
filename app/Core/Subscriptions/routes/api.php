<?php

use App\Core\Subscriptions\Http\Controllers\SubscriptionController;

Route::prefix('api')->group(function () {
    Route::prefix('subscription')->middleware(['auth:api'])->group(function () {
        Route::get("/", [SubscriptionController::class, "index"]);
        Route::get("/plans", [SubscriptionController::class, "plans"]);
    });
});
