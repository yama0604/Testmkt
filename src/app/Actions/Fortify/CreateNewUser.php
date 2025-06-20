<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        // RegisterRequestのバリデーションルールを適用
        Validator::make(
            $input,
            (new RegisterRequest)->rules(),
            (new RegisterRequest)->messages()
        )->validate();

        $user = User::create([
            'user_name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'post_code' => '',
            'address' => '',
            'building_name' => null,
            'image' => null,
        ]);

        Auth::login($user);

        return $user;
    }
}
