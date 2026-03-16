<?php

use App\Core\Webhooks\Http\Controllers\WebhookController;

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/webhooks')->group(function () {
        Route::get('/', [WebhookController::class, 'index']);
        Route::post('/', [WebhookController::class, 'store']);
        Route::put('/{webhook}', [WebhookController::class, 'update']);
        Route::delete('/{webhook}', [WebhookController::class, 'destroy']);
    });
});