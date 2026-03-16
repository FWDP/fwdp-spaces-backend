<?php

namespace App\Core\Webhooks\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'event',
        'url',
        'secret',
        'active',
    ];
}
