<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/listings_show.css') }}">
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
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="header__link-button">ログアウト</button>
            </form>
            <a href="{{ route('mypage.profile') }}" class="header__link">マイページ</a>
            <a href="{{ route('items.sell') }}" class="header__btn-sell">出品</a>
        @else
            <a href="{{ route('login') }}" class="header__link">ログイン</a>
            <a href="{{ route('login') }}" class="header__link">マイページ</a>
            <a href="{{ route('login') }}" class="header__btn-sell">出品</a>
        @endauth
    </div>
</header>

<main class="listing-detail">
    <div class="listing-detail__container">
        <div class="listing-detail__image-area">
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->listing_name }}">
        </div>

        <div class="listing-detail__info-area">
            <h2 class="listing-detail__title">{{ $item->listing_name }}</h2>
            <p class="listing-detail__brand">{{ $item->brand_name }}</p>
            <p class="listing-detail__price">¥{{ number_format($item->price) }}<span class="tax">（税込）</span></p>

            <div class="listing-detail__actions">
                <div class="listing-detail__icon-group">
                    @auth
                        <form method="POST" action="{{ route('items.like', $item->id) }}">
                        @csrf
                            <button type="submit" class="icon-with-count icon--star {{ $item->isLikedBy(Auth::user()) ? 'active' : '' }}">
                                <span class="icon"></span>
                                <span class="count">{{ $item->likes->count() }}</span>
                            </button>
                        </form>
                    @else
                        <div class="icon-with-count icon--star">
                            <span class="icon"></span>
                            <span class="count">{{ $item->likes->count() }}</span>
                        </div>
                    @endauth

                    <div class="icon-with-count icon--comment">
                        <span class="icon"></span>
                        <span class="count">{{ $item->comments->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="purchase-button__wrapper">
                @if ($item->sold_flag === \App\Models\Listing::SOLD_FLAG_SOLD_OUT)
                    <div class="listing-detail__sold-button">SOLD</div>
                @else
                    @auth
                        <a href="{{ route('purchase', ['item' => $item->id]) }}" class="listing-detail__purchase-button">購入手続きへ</a>
                    @else
                        <a href="{{ route('login') }}" class="listing-detail__purchase-button">購入手続きへ</a>
                    @endauth
                @endif
            </div>

            <div class="listing-detail__section">
                <h3>商品説明</h3>
                <p>{!! nl2br(e($item->explanation)) !!}</p>
            </div>

            <div class="listing-detail__section">
                <h3>商品の情報</h3>
                <p><strong>カテゴリー：</strong>
                    @foreach($item->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                    @endforeach
                </p>
                <p><strong>商品の状態：</strong>{{ $item->status_label }}</p>
            </div>

            <div class="listing-detail__section">
            <h3>コメント({{ $item->comments->count() }})</h3>

            @if ($latest)
                <div class="comment">
                    <div class="comment__header">
                        <img src="{{ asset('storage/' . ($latest->user->image ?? 'default-user.png')) }}" class="comment__user-icon">
                        <div class="comment__user-name">{{ $latest->user->user_name }}</div>
                    </div>
                    <div class="comment__box">
                        {{ $latest->comment }}
                    </div>
                </div>
            @endif

            <div class="comment-form">
                @auth
                    <form method="POST" action="{{ route('comments.purchase', ['id' => $item->id]) }}">
                        @csrf
                        <label for="comment" class="comment__label">商品へのコメント</label>
                        <textarea name="comment" id="comment" class="comment__textarea">{{ old('comment') }}</textarea>

                        @error('comment')
                        <div class="error">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="comment__submit-button">コメントを送信する</button>
                    </form>
                @else
                    <label class="comment__label">商品へのコメント</label>
                    <textarea class="comment__textarea" readonly onclick="location.href='{{ route('login') }}'"></textarea>
                    <a href="{{ route('login') }}" class="comment__submit-button">コメントを送信する</a>
                @endauth
            </div>
        </div>
    </div>
</main>
</body>
</html>
