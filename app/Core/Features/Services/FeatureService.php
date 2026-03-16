<?php

namespace App\Core\Features\Services;

use App\Core\Features\Models\Feature;
use App\Models\User;

class FeatureService
{
    public function enabled(string $featureKey): bool
    {
        return Feature::query()->where('key', $featureKey)->where('enabled', true)->exists();
    }

    public function userHasFeature(User $user, string $featureKey): bool
    {
        $feature = Feature::query()->where('key', $featureKey)->firstOrFail();

        $subscription = $user->subscriptions()
            ->latest()
            ->firstOrFail();

        return $subscription->plan->features()->where('features.id', $feature->id)->exists();
    }
}