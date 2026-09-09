<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryAddressRequest extends FormRequest
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
            'delivery_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/\A[0-9+() -]+\z/'],
            'postal_code' => ['required', 'string', 'regex:/\A\d{3}-?\d{4}\z/'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'delivery_name.required' => 'お名前を入力してください。',
            'phone.required' => '電話番号を入力してください。',
            'phone.regex' => '電話番号は半角数字と記号で入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は123-4567の形式で入力してください。',
            'address.required' => '住所を入力してください。',
        ];
    }
}
