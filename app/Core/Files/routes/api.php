<?php

use App\Core\Files\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('api/files')->group(function () {
        Route::post('upload', [FileController::class, 'upload']);
        Route::get('/{file}',[FileController::class, 'show']);
        Route::delete('/{file}',[FileController::class, 'destroy']);
    });
});