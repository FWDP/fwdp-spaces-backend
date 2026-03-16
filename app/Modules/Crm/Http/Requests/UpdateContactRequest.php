<?php

namespace App\Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'email'       => 'nullable|email|unique:crm_contacts,email,' . $this->route('contactId'),
            'phone'       => 'nullable|string|max:50',
            'company'     => 'nullable|string|max:255',
            'type'        => 'nullable|in:lead,customer,partner,vendor',
            'assigned_to' => 'nullable|exists:users,id',
            'notes'       => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}
