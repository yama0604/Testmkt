<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メール認証誘導画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-logo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/authentication_guidance.css') }}">
</head>
<body>
    <header class="header">
        <div>
            <img src="{{ asset('storage/logo.svg') }}" class="header__logo">
        </div>
    </header>

    <main class="main">
        <p class="main__text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a href="{{ route('verification.notice') }}" class="main__button">認証はこちらから</a>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="main__link">認証メールを再送する</button>
        </form>
    </main>
</body>
</html>
