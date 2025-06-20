<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品出品画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-full.css') }}">
    <link rel="stylesheet" href="{{ asset('css/listings.css') }}">
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
            <button class="header__link-button">ログアウト</button>
        </form>
        <a href="/mypage" class="header__link">マイページ</a>
        <a href="{{ route('items.sell') }}" class="header__btn-sell">出品</a>
    </div>
</header>

<main class="main">
    <h2 class="main__title">商品の出品</h2>
    <form method="POST" action="{{ route('items.sellInsert') }}" enctype="multipart/form-data" class="form">
        @csrf

        <div class="form__group">
            <label>商品画像</label>
            <div class="form__image-upload-wrap">
                <img id="image-preview" class="form__image-preview" style="display: none;" alt="preview">
                <input type="file" name="product_image" id="product_image" class="form__image-input">
                <label for="product_image" class="form__image-label">画像を選択する</label>
            </div>
            @error('product_image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <hr>
        <h3>商品の詳細</h3>
        <div class="form__group">
            <label>カテゴリー</label>
            <div class="form__categories">
                @foreach($categories as $category)
                    <label class="form__tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">{{ $category->name }}
                    </label>
                @endforeach
            </div>
            @error('categories')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form__group">
            <label>商品の状態</label>
            <select name="status" class="form__input">
                <option value="">選択してください</option>
                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>良好</option>
                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>未使用に近い</option>
                <option value="2" {{ old('status') === '2' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="3" {{ old('status') === '3' ? 'selected' : '' }}>全体的に状態が悪い</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form__group">
            <label>商品名</label>
            <input type="text" name="listing_name" class="form__input" value="{{ old('listing_name') }}">
            @error('listing_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form__group">
            <label>ブランド名</label>
            <input type="text" name="brand_name" class="form__input" value="{{ old('brand_name') }}">
        </div>

        <div class="form__group">
            <label>商品の説明</label>
            <textarea name="explanation" class="form__input">{{ old('explanation') }}</textarea>
            @error('explanation')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form__group">
            <label>販売価格</label>
            <div class="form__price-wrap">
                <span class="form__yen">¥</span>
                <input type="text" name="price" class="form__input form__input--price" value="{{ old('price') }}">
            </div>
            @error('price')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form__btn-wrap">
            <button type="submit" class="form__btn">出品する</button>
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 商品画像プレビュー
        const imageInput = document.getElementById('product_image');
        const preview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        // 販売価格入力処理
        const priceInput = document.querySelector('input[name="price"]');

        // 数値全角から半角へ変換
        function halfWidth(str) {
            return str.replace(/[０-９]/g, function(s) {
                return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
            });
        }

        // カンマ表示（3桁区切り）
        function formatWithComma(numStr) {
            return numStr.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // 表示する数値の画面上での整形
        priceInput.addEventListener('input', function () {
            const halfWidthValue = halfWidth(priceInput.value);
            const numericVal = Number(halfWidthValue.replace(/[^\d]/g, ''));
            if (numericVal > Number.MAX_SAFE_INTEGER) {
                priceInput.value = formatWithComma(String(Number.MAX_SAFE_INTEGER));
                return;
            }
            const formatted = formatWithComma(halfWidthValue);
            priceInput.value = formatted;
        });

        // カンマ削除定義（DB保存用）
        function removeComma(str) {
            return str.replace(/,/g, '');
        }

        // フォーム送信時カンマ除去
        priceInput.closest('form').addEventListener('submit', function () {
            priceInput.value = removeComma(halfWidth(priceInput.value));
        });
    });
</script>
</body>
</html>