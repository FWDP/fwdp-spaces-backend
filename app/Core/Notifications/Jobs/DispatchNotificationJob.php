<?php

namespace App\Core\Notifications\Jobs;

use App\Core\Notifications\Channel\BroadcastChannel;
use App\Core\Notifications\Channel\EmailChannel;
use App\Core\Notifications\Channel\InAppChannel;
use App\Core\Notifications\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchNotificationJob implements ShouldQueue
{
    use Queueable;

    public Notification $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(InAppChannel::class)->send($this->notification);
        app(EmailChannel::class)->send($this->notification);
        app(BroadcastChannel::class)->send($this->notification);
    }
}
