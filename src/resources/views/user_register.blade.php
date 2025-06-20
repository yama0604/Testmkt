<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-logo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_register.css') }}">
</head>
<body>
    <header class="header">
        <div>
            <img src="{{ asset('storage/logo.svg') }}" class="header__logo">
        </div>
    </header>

    <main class="main">
        <h2 class="main__title">会員登録</h2>
        <form method="POST" action="{{ route('register.perform') }}" class="form">
            @csrf

            <div class="form__group">
                <label class="form__label">ユーザー名</label>
                <input type="text" name="name" class="form__input" value="{{ old('name') }}">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label">メールアドレス</label>
                <input type="text" name="email" class="form__input" value="{{ old('email') }}">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label">パスワード</label>
                <input type="password" name="password" class="form__input">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label">確認用パスワード</label>
                <input type="password" name="confirm_password" class="form__input">
                @error('confirm_password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__btn-wrap">
                <button type="submit" class="form__btn">登録する</button>
            </div>

            <div class="form__login-link">
                <a href="/login">ログインはこちら</a>
            </div>
        </form>
    </main>
</body>
</html>
