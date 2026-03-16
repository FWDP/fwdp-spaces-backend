<?php

namespace App\Listeners;

use App\Core\Notifications\Services\NotificationService;
use App\Models\User;

class CourseUpdatedNotification
{
    protected NotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $course = $event->course;

        $users = User::query()
            ->whereHas('enrollments', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->pluck('id');

        $this->notificationService->notify($users, $course);
    }
}
