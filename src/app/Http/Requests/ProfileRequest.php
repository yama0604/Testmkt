<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image'     => ['nullable', 'image', 'mimes:jpeg,png'],
            'user_name' => ['required'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'   => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes' => '拡張子が.jpegもしくは.pngを選択してください。',
            'user_name.required' => '入力必須項目です。',
            'post_code.required' => '入力必須項目です。',
            'post_code.regex' => 'ハイフンありの8文字で入力する必要があります。',
            'address.required' => '入力必須項目です。',
        ];
    }
}
