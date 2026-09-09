<?php

namespace App\Http\Requests;

use App\PaymentMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30', 'regex:/\A[0-9+() -]+\z/'],
            'shipping_postal_code' => ['required', 'string', 'regex:/\A\d{3}-?\d{4}\z/'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'payment_method' => [
                'required',
                Rule::in(array_map(
                    static fn (PaymentMethod $paymentMethod): string => $paymentMethod->value,
                    PaymentMethod::availableForCheckout(),
                )),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'shipping_name.required' => 'お名前を入力してください。',
            'shipping_phone.required' => '電話番号を入力してください。',
            'shipping_phone.regex' => '電話番号は半角数字と記号で入力してください。',
            'shipping_postal_code.required' => '郵便番号を入力してください。',
            'shipping_postal_code.regex' => '郵便番号は123-4567の形式で入力してください。',
            'shipping_address.required' => '住所を入力してください。',
            'payment_method.required' => 'お支払い方法を選択してください。',
            'payment_method.in' => 'お支払い方法を選び直してください。',
        ];
    }
}
