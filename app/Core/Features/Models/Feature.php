<?php

namespace App\Core\Features\Models;

use App\Core\Subscriptions\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = [
        'key',
        'name',
        'enabled',
    ];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(
            SubscriptionPlan::class,
            'plan_features',
            'feature_id',
            'plan_id'
        );
    }
}
