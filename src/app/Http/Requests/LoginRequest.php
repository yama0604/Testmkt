<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            // 未入力の場合
            'email.required'    => 'メールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',

            // 入力規則違反の場合
            'email.email'       => 'メールアドレスの形式が正しくありません。',
            'password.min'      => 'パスワードは8文字以上で入力してください。',

            // ログイン失敗時（認証されなかった場合）
            'login.failed'      => 'ログイン情報が登録されていません。',
        ];
    }
}
