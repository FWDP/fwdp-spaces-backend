<?php

namespace App\Core\Subscriptions\Models;

use App\Core\Payments\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'end_date',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers / Business Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Check if subscription is currently valid
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active'])
            && $this->end_date !== null
            && $this->end_date >= Carbon::today();
    }

    /**
     * Mark subscription as expired
     */
    public function isExpired(): bool
    {
        return $this->end_date !== null && now()->gt($this->end_date);
    }

    public function expire() : void
    {
        $this->update(['status'  => 'expired']);
    }
}
