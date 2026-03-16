<?php

namespace App\Core\Notifications\Channel;

use App\Core\Notifications\Models\Notification;
use App\Core\Notifications\Models\NotificationDelivery;
use App\Models\User;

class BroadcastChannel
{
    public function send(Notification $notification): void
    {
        NotificationDelivery::query()->create([
            'notification_id' => $notification->id,
            'channel' => 'broadcast',
            'status' => 'sent',
            'created_at' => now()
        ]);
    }
}
