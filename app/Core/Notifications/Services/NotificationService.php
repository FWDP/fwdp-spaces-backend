<?php

namespace App\Core\Notifications\Services;

use App\Core\Notifications\Events\NotificationCreated;
use App\Core\Notifications\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function send(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): Notification {
        $notification = Notification::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $message,
            'data' => $data,
        ]);

        event(new NotificationCreated($notification));

        return $notification;
    }
}
