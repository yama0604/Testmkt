<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品購入画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchases.css') }}">
</head>
<script>
    // ページを戻った時のキャッシュ影響による再購入を防止
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.href = '/';
        }
    });
</script>
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

<main class="purchase">
    <form class="purchase__form" method="POST" action="{{ route('purchase.complete', ['id' => $item->id]) }}">
        @csrf
        <div class="purchase__left">
            <div class="purchase__info-block">
                <div class="purchase__image">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                </div>
                <div>
                    <h2 class="purchase__name">{{ $item->listing_name }}</h2>
                    <p class="purchase__price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="purchase__section purchase__section--payment">
                <label for="payment" class="purchase__section-title">支払い方法</label>
                <select name="payment" id="paymentSelect" class="purchase__select">
                    <option value="">選択してください</option>
                    <option value="0" {{ old('payment') === '0' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="1" {{ old('payment') === '1' ? 'selected' : '' }}>カード払い</option>
                </select>
                @error('payment')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="purchase__section purchase__section--address">
                <div class="purchase__section-header">
                    <label class="purchase__section-title">配送先</label>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="purchase__change-link">変更する</a>
                </div>
                <div>
                    {{-- 表示用 --}}
                    <p>〒 {{ $postCode }}</p>
                    <p>{{ $address }}</p>
                    @if (!empty($building))
                        <p>{{ $building }}</p>
                    @endif

                    {{-- バリデーション用（送信用） --}}
                    <input type="hidden" name="post_code" value="{{ $postCode }}">
                    @error('post_code')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <input type="hidden" name="address" value="{{ $address }}">
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="purchase__right">
            <table class="purchase__summary">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="selected-payment">未選択</td>
                </tr>
            </table>
            <button type="submit" class="purchase__button">購入する</button>
        </div>
    </form>
</main>

<script>
    document.getElementById('paymentSelect').addEventListener('change', function () {
        const selectedText = this.options[this.selectedIndex].text;
        document.getElementById('selected-payment').textContent = selectedText;
    });
</script>
</body>
</html>
