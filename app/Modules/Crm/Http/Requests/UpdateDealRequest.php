<?php

namespace App\Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'contact_id'  => 'sometimes|exists:crm_contacts,id',
            'title'       => 'sometimes|string|max:255',
            'value'       => 'nullable|numeric|min:0',
            'stage'       => 'nullable|in:new,qualified,proposal,negotiation,won,lost',
            'close_date'  => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'notes'       => 'nullable|string',
        ];
    }
}
