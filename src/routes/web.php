<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 会員登録画面
Route::get('/register', [UserController::class, 'register'])
    ->name('register');
Route::middleware(['guest'])
    ->post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.perform');

// メール認証後プロフィール編集
Route::middleware(['auth', 'verified'])
    ->get('/redirect-after-verification', [UserController::class, 'redirectAfterVerification'])
    ->name('verification.redirect');

// ログイン画面
Route::get('/login', [UserController::class, 'login'])->name('login');

// ログイン後プロフィール編集（初回登録時プロフィール未入力のユーザー）
Route::middleware(['auth', 'verified'])
    ->get('/redirect-after-login', [UserController::class, 'redirectAfterLogin'])
    ->name('login.redirect');

// プロフィール編集関連
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [UserController::class, 'mypageProfile'])->name('mypage.profile');
    Route::get('/mypage/profile', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::post('/mypage/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});

// 商品一覧（未ログインでもアクセス可能 但し未ログインはおすすめのみ表示）
Route::get('/', [ItemController::class, 'top'])->name('items.index');

// 出品画面（未ログインはアクセス不可）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sell', [ItemController::class, 'sell'])->name('items.sell');
    Route::post('/sell', [ItemController::class, 'sellInsert'])->name('items.sellInsert');
});

// 商品詳細画面（未ログインでもアクセス可能）
Route::get('/item/{item}', [ItemController::class, 'purchaseShow'])
    ->name('items.show');
// 商品詳細画面（未ログインの場合いいね・コメント不可）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{id}/like', [ItemController::class, 'toggleLike'])->name('items.like');
    Route::post('/item/{id}/comment', [ItemController::class, 'purchaseComment'])->name('comments.purchase');
});

// 購入画面（未ログインはアクセス不可）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/purchase/{item}', [ItemController::class, 'purchase'])->name('purchase');
    Route::post('/purchase/{id}', [ItemController::class, 'complete'])->name('purchase.complete');
});

// 購入画面から住所変更時（未ログインはアクセス不可）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'purchaseAddress'])->name('purchase.address');
    Route::patch('/purchase/address/{item_id}', [ItemController::class, 'purchaseAddressUpdate'])->name('purchase.address.update');
});

Route::get('/purchase/stripe/success/{id}', [ItemController::class, 'stripeSuccess'])
    ->middleware(['auth', 'verified'])
    ->name('purchase.stripe.success');