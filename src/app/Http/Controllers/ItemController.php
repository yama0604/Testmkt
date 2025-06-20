<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use \Stripe\Stripe;
use \Stripe\Checkout\Session as StripeSession;

class ItemController extends Controller
{
    // トップページ
    public function top(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');
        if ($tab === 'mylist') {
            // 初回登録時のプロフィール設定済みならマイリストページへ
            $user = Auth::user();
            if (!$user) {
                return redirect('/');
            }
        $items = $user->likes()->with('listing')->get()->pluck('listing');
        // 検索キーワード
        if ($keyword) {
            $items = $items->filter(function ($item) use ($keyword) {
                return stripos($item->listing_name, $keyword) !== false;
            });
        }
        return view('index', [
            'items' => $items,
            'tab' => 'mylist',
        ]);
    }

    // おすすめ（ログイン不要）
    $items = Listing::query();

    if ($keyword) {
        $items->where('listing_name', 'like', '%' . $keyword . '%');
    }

    // ログイン済みなら自分の商品を除外
    if (Auth::check()) {
        $items->where('user_id', '!=', Auth::id());
    }

    return view('index', [
        'items' => $items->get(),
        'tab' => 'recommend',
    ]);
    }

    // 商品出品画面の表示（ログイン必須）
    public function sell()
    {
        $categories = Category::all();
        return view('listings', compact('categories'));
    }

    // 商品出品処理
    public function sellInsert(ExhibitionRequest $request)
    {
        // 画像保存
        $imagePath = $request->file('product_image')->store('product_images', 'public');
        // 商品登録
        $listing = Listing::create([
            'user_id'      => Auth::id(),
            'listing_name' => $request->listing_name,
            'brand_name'   => $request->brand_name,
            'price'        => $request->price,
            'explanation'  => $request->explanation,
            'status'       => $request->status,
            'image'        => $imagePath,
            'sold_flag'    => Listing::SOLD_FLAG_ON_SALE,
        ]);
        // カテゴリの中間テーブル登録
        $listing->categories()->sync($request->categories);
        return redirect('/mypage')->with('status', '商品を出品しました');
    }

    // 商品詳細画面の表示
    public function purchaseShow(Listing $item)
    {
        $item->load([
            'categories',
            'user',
            'comments.user',
        ]);
        $latest = $item->comments->sortByDesc('created_at')->first();
        return view('listings_show', compact('item', 'latest'));
    }

    // 商品詳細画面のいいねボタン処理
    public function toggleLike($id)
    {
        $item = Listing::findOrFail($id);
        $user = auth()->user();
        $like = Like::where('user_id', $user->id)->where('listing_id', $item->id)->first();
        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'listing_id' => $item->id,
            ]);
        }
        return back();
    }

    // 商品詳細画面のコメント処理
    public function purchaseComment(CommentRequest $request, $id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'listing_id' => $id,
            'comment' => $request->comment,
        ]);
        return redirect()->route('items.show', ['item' => $id]);
    }

    // 商品購入画面の表示
    public function purchase(Listing $item)
    {
        $user = Auth::user();

        // purchases.blade.phpのpageshowとセットで使用
        // ページを戻った時のキャッシュ影響による再購入を防止
        if ($item->sold_flag === Listing::SOLD_FLAG_SOLD_OUT) {
            return redirect('/')
                ->with('status', 'この商品はすでに購入済みです。');
        }
        // セッションから住所情報を取得（なければユーザー情報を使う）
        $postCode = session('purchase_address.post_code', $user->post_code);
        $address  = session('purchase_address.address', $user->address);
        $building = session('purchase_address.building', $user->building_name);
        return view('purchases', [
            'item' => $item,
            'user' => $user,
            'postCode' => $postCode,
            'address' => $address,
            'building' => $building,
        ]);
    }

    // 送付先住所変更画面の表示
    public function purchaseAddress($item_id)
    {
        $item = Listing::findOrFail($item_id);
        $user = Auth::user();
        // ユーザー情報を初期表示用に使う
        $purchase = Purchase::where('listing_id', $item_id)
            ->where('user_id', $user->id)
            ->first();
        $postCode = $purchase->shipping_post_code ?? $user->post_code;
        $address  = $purchase->shipping_address ?? $user->address;
        $building = $purchase->shipping_building_name ?? $user->building_name;
        return view('purchases_address_update', [
            'item' => $item,
            'postCode' => $postCode,
            'address' => $address,
            'building' => $building,
        ]);
    }

    // 送付先住所変更処理
    public function purchaseAddressUpdate(AddressRequest $request, $item_id)
    {
        $request->validate([
            'post_code' => 'required|string',
            'address' => 'required|string',
            'building' => 'nullable|string',
        ]);
        session([
            'purchase_address.post_code' => $request->post_code,
            'purchase_address.address' => $request->address,
            'purchase_address.building' => $request->building,
        ]);
        return redirect()->route('purchase', ['item' => $item_id])
            ->with('success', '住所を更新しました。');
    }

    // 商品購入処理
    public function complete(PurchaseRequest $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $user = Auth::user();
        if ($listing->sold_flag === Listing::SOLD_FLAG_SOLD_OUT) {
            return redirect('/')->with('status', 'この商品はすでに売り切れています。');
        }
        // 二重購入チェック
        $already = Purchase::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->exists();
        if ($already) {
            return redirect('/')->with('status', 'この商品はすでに購入済みです。');
        }
        // 住所取得（セッション or ユーザー）
        $postCode = session('purchase_address.post_code', $user->post_code);
        $address  = session('purchase_address.address', $user->address);
        $building = session('purchase_address.building', $user->building_name);
        // 即購入登録
        Purchase::create([
            'user_id' => $user->id,
            'listing_id' => $listing->id,
            'payment' => $request->payment,
            'shipping_post_code' => $postCode,
            'shipping_address' => $address,
            'shipping_building_name' => $building,
        ]);
        // 売り切れにする
        $listing->sold_flag = Listing::SOLD_FLAG_SOLD_OUT;
        $listing->save();
        session()->forget('purchase_address');
        // Stripeの決済画面に遷移
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $listing->listing_name],
                    'unit_amount' => $listing->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url('/'),
        ]);
        return redirect($session->url);
    }


}
