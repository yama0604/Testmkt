<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
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

    <main class="main">
        <div class="mypage__user-info">
            <img src="{{ asset('storage/' . ($user->image ?? 'default-user.png')) }}" class="mypage__avatar">
            <p class="mypage__username">{{ $user->user_name }}</p>
            <a href="{{ route('user.profile.edit') }}" class="mypage__edit-button">プロフィールを編集</a>
        </div>

        <div class="tab-menu">
            <a href="?tab=sell" class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="?tab=buy" class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>

        <div class="item-list">
            @forelse ($items as $item)
                <div class="item-card">
                    <div class="item-card__image">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->listing_name }}">
                    </div>
                    <div class="item-card__name">{{ $item->listing_name }}</div>
                </div>
            @empty
                {{-- 商品が存在しない場合は何も表示しない --}}
            @endforelse
        </div>
    </main>
</body>
</html>
