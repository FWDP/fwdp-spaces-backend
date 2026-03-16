<?php

use App\Core\Payments\Http\Controllers\PaymentController;

Route::prefix('api')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/payments/checkout', [PaymentController::class, 'checkout']);
    });
});
