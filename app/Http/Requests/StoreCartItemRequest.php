<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    /** @return array<int, callable(Validator): void> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            $product = Product::find($this->integer('product_id'));

            if ($product !== null && $this->integer('quantity') > $product->stock) {
                $validator->errors()->add('quantity', '在庫数を超える数量は選択できません。');
            }
        }];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'quantity.min' => '1個以上選択してください。',
            'quantity.max' => '一度に購入できるのは10個までです。',
        ];
    }
}
