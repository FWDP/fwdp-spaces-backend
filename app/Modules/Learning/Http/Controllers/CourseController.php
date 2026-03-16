<?php

namespace App\Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Learning\Http\Requests\CreateCourseRequest;
use App\Modules\Learning\Models\Course;
use App\Modules\Learning\Services\CourseService;

class CourseController extends Controller
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->listCourse();
    }

    public function show(Course $course)
    {
        return $this->service->getCourse($course);
    }

    public function store(CreateCourseRequest $request)
    {
        return $this->service->createCourse($request->validated());
    }
}
