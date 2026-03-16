<?php

namespace App\Core\Subscriptions\Services;

use App\Core\Subscriptions\Models\Subscription;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionService
{
    public function createTrial(User $user): ?Subscription
    {
        $plan = SubscriptionPlan::where('code', 'FREE')->first();

        if (! $plan) {
            return null;
        }

        $trialEndsAt = now()->addDays($plan->trial_days ?? 14);

        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'trial_ends_at' => $trialEndsAt,
            'end_date' => $trialEndsAt,
        ]);
    }

    public function getCurrentSubscription(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->with('plan')
            ->latest()
            ->first();
    }
}
