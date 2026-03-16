<?php

namespace App\Core\Security\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'ip_address',
        'successful',
        'created_at',
    ];
}
