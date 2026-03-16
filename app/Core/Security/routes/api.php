<?php

use App\Core\Security\Http\Controllers\SecurityController;

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/security')->group(function () {
        Route::get('/blocked-ips', [SecurityController::class, 'blockedIps']);
        Route::post('/block-ip', [SecurityController::class, 'blockIp']);
        Route::delete('/delete-ip', [SecurityController::class, 'unblockIp']);
    });
});
