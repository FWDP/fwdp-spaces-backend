<?php

namespace App\Modules\Learning\Services;

use App\Modules\Learning\Models\Course;

class CourseService
{
    public function listCourse(): \Illuminate\Database\Eloquent\Collection
    {
        return Course::query()->where('is_published', 1)->get();
    }

    public function getCourse(Course $course): Course
    {
        return $course->load('sections.lessons');
    }

    public function createCourse(array $data): Course
    {
        return Course::query()->create($data);
    }
}