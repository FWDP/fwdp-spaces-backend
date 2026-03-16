<?php

namespace App\Core\Notifications\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'aggregation_key',
        'aggregation_count',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($notification) {
            if (! $notification->uuid) {
                $notification->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
