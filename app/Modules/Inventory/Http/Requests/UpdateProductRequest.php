<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'sku'         => 'sometimes|string|unique:products,sku,' . $this->route('productId'),
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'cost'        => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit_id'     => 'nullable|exists:units_of_measure,id',
            'is_active'   => 'boolean',
        ];
    }
}
