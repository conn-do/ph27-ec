<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<mixed>> */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'min_price' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:2147483647', Rule::when($this->filled('min_price'), 'gte:min_price')],
            'sort' => ['nullable', Rule::in(['newest', 'price_asc', 'price_desc', 'name'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
