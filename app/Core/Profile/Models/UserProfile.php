<?php

namespace App\Core\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'avatar_url',
        'avatar_path'
    ];
    public function getAvatarAttribute()
    {
        if ($this->avatar_path)
        {
            return asset('storage/'.$this->avatar_path);
        }

        return $this->avatar_url ?? null;
    }
}
