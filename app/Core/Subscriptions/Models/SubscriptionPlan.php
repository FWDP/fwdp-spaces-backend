<?php

namespace App\Core\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'features',
    ];

    protected $casts = [
      'features' => 'array',
    ];

    public function subscriptions() : hasMany
    {
        return $this->hasMany(Subscription::class, 'subscription_plan_id');
    }
}
