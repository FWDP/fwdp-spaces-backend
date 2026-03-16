<?php

namespace App\Modules\Learning\Services;

use App\Modules\Learning\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
    public function listCourse(): Collection
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
