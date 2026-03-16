<?php

namespace App\Modules\JobBoard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $fillable = [
        'job_id',
        'applicant_id',
        'status',
        'cover_letter',
        'resume_path',
        'notes',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobListing::class, 'job_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}
