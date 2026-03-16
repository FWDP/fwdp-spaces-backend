<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:in,out,transfer,adjustment',
            'quantity' => 'required|numeric|min:0.0001',
            'note' => 'nullable|string',
        ];
    }
}
