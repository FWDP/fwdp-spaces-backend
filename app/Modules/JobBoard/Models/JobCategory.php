<?php

namespace App\Modules\JobBoard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    protected $table = 'job_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function listings(): HasMany
    {
        return $this->hasMany(JobListing::class, 'category_id');
    }
}
