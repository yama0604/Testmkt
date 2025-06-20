<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>送付先住所変更画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchases_address_update.css') }}">
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
        <a href="{{ route('mypage.profile') }}" class="header__link">マイページ</a>
        <a href="{{ route('items.sell') }}" class="header__btn-sell">出品</a>
    </div>
</header>

<main class="address">
    <h2 class="address__title">住所の変更</h2>

    <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" class="address__form">
        @csrf
        @method('PATCH')

        <div class="address__form-group">
            <label for="post_code">郵便番号</label>
            <input type="text" id="post_code" name="post_code" value="{{ old('post_code', $postCode) }}">
            @error('post_code')
                    <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="address__form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $address) }}">
            @error('address')
                    <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="address__form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $building) }}">
        </div>

        <button type="submit" class="address__submit">更新する</button>
    </form>
</main>
</body>
</html>
