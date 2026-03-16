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
            ->whereHas('lesson.section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count();

        $total = Lesson::query()
            ->whereHas('section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count();

        if ($total === 0) {
            return 0;
        }

        return intval(($completed / $total) * 100);
    }

    public function updateProgressFromLesson($userId, $lessonId): int
    {
        $lesson = Lesson::query()->with('section')->findOrFail($lessonId);

        return $this->upgradeProgress($userId, $lesson->section->course_id);
    }
}