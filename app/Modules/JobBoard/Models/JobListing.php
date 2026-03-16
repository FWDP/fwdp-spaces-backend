<?php

namespace App\Modules\JobBoard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    protected $table = 'job_listings';

    protected $fillable = [
        'category_id',
        'posted_by',
        'title',
        'description',
        'requirements',
        'location',
        'is_remote',
        'salary_min',
        'salary_max',
        'currency',
        'type',
        'status',
        'deadline',
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'deadline' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs', 'job_id', 'user_id')
            ->withPivot('saved_at');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
