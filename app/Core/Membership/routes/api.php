<?php

use App\Core\Membership\Http\Controllers\PermissionController;
use App\Core\Membership\Http\Controllers\RoleController;
use App\Core\Membership\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'permission:manage_roles'])->group(function () {
    Route::prefix('api/membership')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{role}', [RoleController::class, 'show']);
            Route::put('/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'destroy']);

            Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions']);
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
        });

        Route::prefix('users')->group(function () {
            Route::post('/{user}/roles', [UserRoleController::class, 'assign']);
            Route::delete('/{user}/roles', [UserRoleController::class, 'remove']);
        });
    });
});
