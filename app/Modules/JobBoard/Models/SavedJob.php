<?php

namespace App\Modules\JobBoard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedJob extends Model
{
    protected $table = 'saved_jobs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'job_id',
        'saved_at',
    ];

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobListing::class, 'job_id');
    }
}
