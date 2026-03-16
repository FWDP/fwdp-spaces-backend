<?php

namespace App\Modules\Learning\Services;

use App\Modules\Learning\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentService
{
    public function enroll(int $userId, int $courseId): Enrollment
    {
        return Enrollment::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);
    }

    public function unenroll(int $userId, int $courseId): void
    {
        Enrollment::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();
    }

    public function userEnrollments(int $userId): Collection
    {
        return Enrollment::query()
            ->with('course')
            ->where('user_id', $userId)
            ->get();
    }
}
