<?php

use App\Modules\Crm\Http\Controllers\ActivityController;
use App\Modules\Crm\Http\Controllers\ContactController;
use App\Modules\Crm\Http\Controllers\DealController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/crm')->middleware('auth:api')->group(function () {

    // Contacts
    Route::get('contacts', [ContactController::class, 'index']);
    Route::post('contacts', [ContactController::class, 'store']);
    Route::get('contacts/{contactId}', [ContactController::class, 'show']);
    Route::put('contacts/{contactId}', [ContactController::class, 'update']);
    Route::delete('contacts/{contactId}', [ContactController::class, 'destroy']);

    // Deals
    Route::get('deals', [DealController::class, 'index']);
    Route::post('deals', [DealController::class, 'store']);
    Route::get('deals/{dealId}', [DealController::class, 'show']);
    Route::put('deals/{dealId}', [DealController::class, 'update']);
    Route::patch('deals/{dealId}/stage', [DealController::class, 'updateStage']);
    Route::delete('deals/{dealId}', [DealController::class, 'destroy']);

    // Activities
    Route::get('activities', [ActivityController::class, 'index']);
    Route::post('activities', [ActivityController::class, 'store']);
    Route::patch('activities/{activityId}/complete', [ActivityController::class, 'complete']);
    Route::delete('activities/{activityId}', [ActivityController::class, 'destroy']);
});
