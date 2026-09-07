<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'productId' => [$this->isMethod('post') ? 'required' : 'sometimes', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:'.config('shop.max_quantity')],
        ];
    }

    public function messages(): array
    {
        return [
            'productId.*' => '商品が見つかりません。商品一覧から選び直してください。',
            'quantity.*' => '数量は1〜'.config('shop.max_quantity').'個の整数で入力してください。',
        ];
    }
}
