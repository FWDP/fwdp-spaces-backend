<?php

namespace App\Listeners;

use App\Core\Profile\Services\ProfileService;
use App\Events\UserRegistered;

class CreateUserProfile
{
    protected ProfileService $profileService;

    /**
     * Create the event listener.
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->profileService->updateProfile($event->user, []);
    }
}
