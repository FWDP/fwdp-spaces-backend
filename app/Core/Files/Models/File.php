<?php

namespace App\Core\Files\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'user_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
