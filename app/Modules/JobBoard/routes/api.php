<?php

use App\Modules\JobBoard\Http\Controllers\JobApplicationController;
use App\Modules\JobBoard\Http\Controllers\JobCategoryController;
use App\Modules\JobBoard\Http\Controllers\JobListingController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/job-board')->group(function () {

    // Public: browse & view jobs
    Route::get('jobs', [JobListingController::class, 'index']);
    Route::get('jobs/{jobId}', [JobListingController::class, 'show']);
    Route::get('categories', [JobCategoryController::class, 'index']);

    // Authenticated routes
    Route::middleware('auth:api')->group(function () {

        // Employer: manage own listings
        Route::get('my-jobs', [JobListingController::class, 'myListings']);
        Route::post('jobs', [JobListingController::class, 'store']);
        Route::put('jobs/{jobId}', [JobListingController::class, 'update']);
        Route::patch('jobs/{jobId}/publish', [JobListingController::class, 'publish']);
        Route::patch('jobs/{jobId}/close', [JobListingController::class, 'close']);
        Route::delete('jobs/{jobId}', [JobListingController::class, 'destroy']);

        // Employer: manage applications on own jobs
        Route::get('jobs/{jobId}/applications', [JobApplicationController::class, 'forJob']);
        Route::patch('applications/{applicationId}/status', [JobApplicationController::class, 'updateStatus']);

        // Applicant: apply & manage applications
        Route::post('jobs/{jobId}/apply', [JobApplicationController::class, 'apply']);
        Route::get('my-applications', [JobApplicationController::class, 'myApplications']);
        Route::delete('applications/{applicationId}', [JobApplicationController::class, 'withdraw']);

        // Save/unsave jobs
        Route::post('jobs/{jobId}/save', [JobListingController::class, 'toggleSave']);
        Route::get('saved-jobs', [JobListingController::class, 'savedJobs']);
    });

    // Admin: manage categories
    Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
        Route::post('categories', [JobCategoryController::class, 'store']);
        Route::put('categories/{categoryId}', [JobCategoryController::class, 'update']);
        Route::delete('categories/{categoryId}', [JobCategoryController::class, 'destroy']);
    });
});
