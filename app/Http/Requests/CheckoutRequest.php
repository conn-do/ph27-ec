<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    protected $redirectRoute = 'checkout';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'regex:/^[0-9]{3}-?[0-9]{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+() -]{10,20}$/'],
            'checkout_token' => ['required', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.*' => 'お名前を100文字以内で入力してください。',
            'postal_code.*' => '郵便番号を123-4567の形式で入力してください。',
            'address.*' => '住所（都道府県・市区町村・番地）を255文字以内で入力してください。',
            'phone.*' => '電話番号を10〜20文字の数字・ハイフンで入力してください。',
            'checkout_token.*' => '注文画面を開き直して、もう一度お試しください。',
        ];
    }
}
