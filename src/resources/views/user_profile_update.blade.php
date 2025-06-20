<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール設定画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_profile_update.css') }}">
</head>
<body>
    <header class="header">
        <div>
            <img src="{{ asset('storage/logo.svg') }}" class="header__logo">
        </div>
        <div class="header__center">
            <form method="GET" action="{{ url('/') }}">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="header__search" placeholder="なにをお探しですか？">
                <input type="hidden" name="tab" value="mylist">
                <button type="submit" style="display: none;">検索</button>
            </form>
        </div>
        <div class="header__right">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="header__link-button">ログアウト</button>
            </form>
            <a href="{{ auth()->check() && auth()->user()->post_code && auth()->user()->address? route('mypage.profile') : route('user.profile.edit') }}"class="header__link">マイページ</a>
            <a href="{{ auth()->check() && auth()->user()->post_code && auth()->user()->address? route('items.sell') : route('user.profile.edit') }}"class="header__btn-sell">出品</a>
        </div>
    </header>

    <main class="main">
        <h2 class="main__title">プロフィール設定</h2>
        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="form">
            @csrf

            <div class="form__image-row">
                <div class="form__image-preview-wrap">
                <img id="preview" src="{{ asset('storage/' . (auth()->user()->image ?? 'default-user.png')) }}" class="form__image-preview">
                    @error('image')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form__image-select-wrap">
                    <label for="image" class="form__image-label">画像を選択する</label>
                    <input type="file" name="image" id="image" class="form__image-input">
                </div>
            </div>

            <div class="form__group">
                <label>ユーザー名</label>
                <input type="text" name="user_name" class="form__input" value="{{ old('user_name', auth()->user()->user_name) }}">
                @error('user_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label>郵便番号</label>
                <input type="text" name="post_code" class="form__input" value="{{ old('post_code', auth()->user()->post_code) }}">
                @error('post_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label>住所</label>
                <input type="text" name="address" class="form__input" value="{{ old('address', auth()->user()->address) }}">
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form__group">
                <label>建物名</label>
                <input type="text" name="building_name" class="form__input" value="{{ old('building_name', auth()->user()->building_name) }}">
            </div>

            <div class="form__btn-wrap">
                <button type="submit" class="form__btn">更新する</button>
            </div>
        </form>
    </main>
    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>
