<?php

namespace App\Listeners;

use App\Core\Subscriptions\Services\SubscriptionService;
use App\Events\UserRegistered;

class CreateTrialSubscription
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->subscriptionService->createTrial($event->user);
    }
}
