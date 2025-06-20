<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required_with:password', 'min:8', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            // 未入力の場合
            'name.required' => 'お名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',

            // 入力規則違反の場合
            'email.email' => 'メールアドレスは正しい形式で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'confirm_password.required_with' => 'パスワードと一致しません。',
            'confirm_password.min' => 'パスワードと一致しません。',
            'confirm_password.same' => 'パスワードと一致しません。',
        ];
    }
}
