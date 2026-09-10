<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'regex:/^\\d{3}-?\\d{4}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'checkout_token' => ['required', 'uuid'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'お名前を入力してください。',
            'customer_name.max' => 'お名前は255文字以内で入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は123-4567形式で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.max' => '住所は1000文字以内で入力してください。',
            'checkout_token.required' => '注文情報を確認できません。もう一度お試しください。',
            'checkout_token.uuid' => '注文情報を確認できません。もう一度お試しください。',
        ];
    }
}
