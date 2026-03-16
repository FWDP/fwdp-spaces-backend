<?php

use App\Core\Subscriptions\Http\Controllers\SubscriptionController;

Route::prefix('api/subscription')->middleware(['auth:api'])->group(function () {
    Route::get("/", [SubscriptionController::class, "current"]);
    Route::get("/plans", [SubscriptionController::class, "plans"]);
});
