<?php

namespace App\Core\Notifications\Http\Controllers;

use App\Core\Notifications\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function broadcast(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        User::cursor()->each(function ($user) use ($data) {
            $this->notificationService->send(
                $user,
                'admin_broadcast',
                $data['title'],
                $data['body'],
            );
        });

        return response()->json([
            'message' => 'Notification has been sent.'
        ]);
    }
}
