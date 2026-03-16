<?php

namespace App\Modules\JobBoard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'category_id'  => 'nullable|exists:job_categories,id',
            'location'     => 'nullable|string|max:255',
            'is_remote'    => 'boolean',
            'salary_min'   => 'nullable|integer|min:0',
            'salary_max'   => 'nullable|integer|min:0|gte:salary_min',
            'currency'     => 'nullable|string|max:10',
            'type'         => 'required|in:full_time,part_time,contract,freelance,internship',
            'deadline'     => 'nullable|date|after:today',
        ];
    }
}
