<?php

namespace App\Core\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'channel',
        'status',
        'error',
        'created_at',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }
}
