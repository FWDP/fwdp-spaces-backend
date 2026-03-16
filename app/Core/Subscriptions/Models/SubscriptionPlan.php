<?php

namespace App\Core\Subscriptions\Models;

use App\Core\Features\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price',
        'trial_days',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'plan_features',
            'plan_id',
            'feature_id'
        );
    }
}
