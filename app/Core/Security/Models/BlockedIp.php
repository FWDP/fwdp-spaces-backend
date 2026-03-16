<?php

namespace App\Core\Security\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_at',
    ];
}
