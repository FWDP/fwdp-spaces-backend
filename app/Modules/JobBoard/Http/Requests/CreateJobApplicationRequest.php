<?php

namespace App\Modules\JobBoard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cover_letter' => 'nullable|string',
            'resume_path' => 'nullable|string|max:500',
        ];
    }
}
