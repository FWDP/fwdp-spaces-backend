<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string',
            'address'   => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
