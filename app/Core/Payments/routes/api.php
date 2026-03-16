<?php

use App\Core\Payments\Http\Controllers\PaymentController;
use App\Core\Payments\Http\Controllers\PaymentWebhookController;

Route::prefix('api/payments')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/checkout', [PaymentController::class, 'checkout']);
    });

    Route::post('/webhook', [PaymentWebhookController::class, 'handle']);
});
