<?php

namespace App\Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:call,meeting,note,task,email',
            'subject' => 'required|string|max:255',
            'body' => 'nullable|string',
            'contact_id' => 'required|exists:crm_contacts,id',
            'deal_id' => 'nullable|exists:crm_deals,id',
            'scheduled_at' => 'nullable|date',
        ];
    }
}
