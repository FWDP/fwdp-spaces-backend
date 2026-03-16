<?php

namespace App\Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
      'course_id',
      'title',
      'order'
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
