<?php

namespace App\Core\Notifications\Http\Controllers;

use App\Core\Notifications\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);
    }

    function markRead(Notification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.'
        ]);
    }
}
