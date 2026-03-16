<?php

namespace App\Core\Profile\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $appends = ['avatar_url'];

    protected $hidden = ['avatar_path'];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'avatar_url'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getAvatarAttribute()
    {
        if ($this->avatar_path)
        {
            return asset('storage/'.$this->avatar_path);
        }

        return $this->avatar_url ?? null;
    }
}
