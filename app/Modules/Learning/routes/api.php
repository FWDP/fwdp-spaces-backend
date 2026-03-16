<?php

use App\Modules\Learning\Http\Controllers\CourseController;
use App\Modules\Learning\Http\Controllers\LessonController;
use App\Modules\Learning\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/learning')->middleware('auth:api')->group(function () {

    // Courses
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);
    Route::post('courses', [CourseController::class, 'store']);

    // Lessons
    Route::post('lessons/{lesson}/complete', [LessonController::class, 'complete']);

    // Quiz
    Route::get('quiz/{quiz}', [QuizController::class, 'show']);
    Route::post('quiz/{quiz}/submit', [QuizController::class, 'submit']);
});
