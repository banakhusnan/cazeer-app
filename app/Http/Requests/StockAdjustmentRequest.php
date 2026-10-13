<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StockAdjustmentRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'adjustment'])],
            'quantity' => ['required', 'integer', 'min:0'],
            'notes' => ['required', 'string', 'max:1000'],
        ];
    }
}
