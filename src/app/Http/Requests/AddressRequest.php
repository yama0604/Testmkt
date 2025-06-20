<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_code'  => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'    => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'post_code.required'  => '配送先郵便番号を入力してください。',
            'post_code.regex'     => '郵便番号はハイフンありの8文字で入力する必要があります。',
            'address.required'    => '配送先住所を入力してください。',
        ];
    }
}
