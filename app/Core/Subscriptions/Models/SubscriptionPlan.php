<?php

namespace App\Core\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function subscriptions() : hasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
