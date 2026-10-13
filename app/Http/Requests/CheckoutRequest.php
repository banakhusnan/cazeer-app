<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CheckoutRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris'])],
            'paid_amount' => ['nullable', 'integer', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'print_receipt' => ['required', 'boolean'],
        ];
    }
}
