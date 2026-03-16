<?php

namespace App\Core\Messaging\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchMessageJob implements ShouldQueue
{
    use Queueable;

    public object $event;

    /**
     * Create a new job instance.
     */
    public function __construct(object $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event($this->event);
    }
}
