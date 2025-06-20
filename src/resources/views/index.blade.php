<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    <header class="header">
        <div>
            <img src="{{ asset('storage/logo.svg') }}" class="header__logo">
        </div>
        <div class="header__center">
            <form method="GET" action="{{ url('/') }}">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="header__search" placeholder="なにをお探しですか？">
                @if (request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
            </form>
        </div>
        <div class="header__right">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="header__link-button">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="header__link">ログイン</a>
            @endauth

            <a href="{{ auth()->check() ? route('mypage.profile') : route('login') }}" class="header__link">マイページ</a>

            <a href="{{ auth()->check() ? route('items.sell') : route('login') }}" class="header__btn-sell">出品</a>
        </div>
    </header>

    <div class="tab-menu">
        <a href="{{ request('keyword') ? url('/?keyword=' . urlencode(request('keyword'))) : url('/') }}">おすすめ</a>
        <a href="{{ auth()->check() ? (request('keyword') ? url('/?tab=mylist&keyword=' . urlencode(request('keyword'))) : url('/?tab=mylist')) : route('login') }}">マイリスト</a>

    </div>

    <main class="main">
        <div class="item-list">
        @forelse ($items as $item)
            @if (!auth()->check() || auth()->id() !== $item->user_id)
                <div class="item-card">
                    <a href="{{ url('/item/' . $item->id) }}" class="item-card__link">
                        <div class="item-card__image">
                            @if (!empty($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->listing_name }}">
                                @if ($item->sold_flag === \App\Models\Listing::SOLD_FLAG_SOLD_OUT)
                                    <div class="item-card__sold">SOLD</div>
                                @endif
                            @else
                                <div class="no-image">画像なし</div>
                            @endif
                        </div>
                        <div class="item-card__name">{{ $item->listing_name }}</div>
                    </a>
                </div>
            @endif
        @empty
            {{-- 商品が存在しない場合は何も表示しない --}}
        @endforelse

        </div>
    </main>
</body>
</html>