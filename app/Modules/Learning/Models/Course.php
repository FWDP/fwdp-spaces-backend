<?php

namespace App\Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'is_published',
    ];

    public function category(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
