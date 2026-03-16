<?php

namespace App\Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Learning\Models\Lesson;
use App\Modules\Learning\Models\LessonCompletion;
use App\Modules\Learning\Services\ProgressService;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function complete($lesson)
    {
        $lessonId = $lesson instanceof Lesson ? $lesson->id : $lesson;
        $user = Auth::user();

        $completion = LessonCompletion::query()->firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
        ]);

        $progress = $this->progressService->updateProgressFromLesson($user->id, $lessonId);

        return response()->json([
            'message' => 'Lesson completed',
            'completion' => $completion,
            'progress' => $progress,
        ]);
    }
}
