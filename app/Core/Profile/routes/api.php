<?php

use App\Core\Profile\Http\Controllers\Admin\AdminProfileController;
use App\Core\Profile\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
     Route::prefix('profile')->group(function () {
         Route::get('/', [ProfileController::class, 'show']);
         Route::post('/', [ProfileController::class, 'update']);
         Route::post('/avatar', [ProfileController::class, 'uploadAvatar']);
     });

     Route::prefix('admin')->middleware([
         'auth:api',
         'role:ADMIN'
     ])->group(function () {
         Route::prefix('profiles')->group(function () {
             Route::get('/', [AdminProfileController::class, 'index']);
             Route::get('/{id}', [AdminProfileController::class, 'show']);
         });
     });
 });
