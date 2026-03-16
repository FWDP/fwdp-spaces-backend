<?php

namespace App\Core\Payments\Models;

use App\Core\Subscriptions\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount',
        'status',
        'provider',
        'provider_reference',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isSuccessful() : bool
    {
        return $this->status === 'success';
    }
}
