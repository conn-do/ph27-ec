<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:'.config('shop.cart.max_quantity_per_item')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.min' => 'かずは 1こ いじょう を えらんでね。',
            'quantity.max' => 'いちどに かえるのは :max こ までだよ。',
            'quantity.required' => 'かずを いれてね。',
            'quantity.integer' => 'かずは すうじで いれてね。',
        ];
    }
}
