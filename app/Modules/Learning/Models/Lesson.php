<?php

namespace App\Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'video_url',
        'duration',
        'order',
    ];
}
