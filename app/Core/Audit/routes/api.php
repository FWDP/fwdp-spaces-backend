<?php

use App\Core\Audit\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    Route::prefix('api/audit-logs')->group(function () {
        Route::get('/', [AuditLogController::class, 'index']);
        Route::get('/{auditLog}', [AuditLogController::class, 'show']);
    });
});