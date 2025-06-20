<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // 会員登録処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // Fortifyに対応する各views
        Fortify::registerView(fn () => view('user_register'));
        Fortify::verifyEmailView(fn () => view('authentication_guidance'));
        Fortify::loginView(fn () => view('user_login'));

        // ログイン処理（FormRequest）
        Fortify::authenticateUsing(function () {
            $request = app(LoginRequest::class);
            Validator::make(
                $request->all(),
                $request->rules(),
                $request->messages()
            )->validate();

            // 認証チェック
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                // プロフィール未入力ならフラグをセット
                if (empty($user->post_code) || empty($user->address)) {
                    Session::put('redirect_to_profile', true);
                }
                return $user;
            }

            // 認証失敗
            throw ValidationException::withMessages([
                'login' => [$request->messages()['login.failed']],
            ]);
        });

        // ログインレート制限（1分間に10回）
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by((string) $request->email . $request->ip());
        });
    }
}
