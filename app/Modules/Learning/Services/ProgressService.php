<?php

namespace App\Modules\Learning\Services;



use App\Modules\Learning\Models\Lesson;
use App\Modules\Learning\Models\LessonCompletion;

class ProgressService
{
    public function upgradeProgress($userId, $courseId): int
    {
        $completed = LessonCompletion::query()
            ->where('user_id', $userId)
            ->count();

        $total = Lesson::query()
            ->whereHas('section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count();

        return intval(($completed/$total) * 100);
    }
}