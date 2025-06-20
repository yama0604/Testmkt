<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Models\Purchase;

class UserController extends Controller
{
    // ログイン画面の表示
    public function login()
    {
        session()->forget('url.intended');
        return view('user_login');
    }

    // 会員登録画面の表示
    public function register()
    {
        return view('user_register');
    }

    // メール認証後の遷移先画面表示（プロフィール編集画面 又は 商品一覧画面）
    public function redirectAfterVerification()
    {
        $user = Auth::user();
        return (empty($user->post_code) || empty($user->address))
            ? redirect()->route('user.profile.edit')
            : redirect('/?tab=mylist');
    }

    // 初回会員登録時にプロフィール未入力の場合、プロフィール編集画面を表示
    public function redirectAfterLogin()
    {
        $user = Auth::user();
        if (Session::pull('redirect_to_profile') || empty($user->post_code) || empty($user->address)) {
            return redirect()->route('user.profile.edit');
        }
        return redirect('/?tab=mylist');
    }

    // プロフィール編集画面の表示
    public function editProfile()
    {
        $user = Auth::user();
        $profileIncomplete = empty($user->post_code) || empty($user->address);
        return view('user_profile_update', compact('profileIncomplete'));
    }

    // プロフィール更新処理
    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();

        // プロフィール画像の保存
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $directory = 'profile_images';

            // ファイル名をユニーク処理
            $filename = time() . '_' . $originalName;

            // 保存実行
            $image->storeAs($directory, $filename, 'public');

            // DBに保存するパスを設定
            $user->image = $directory . '/' . $filename;
        }

        // 各プロフィール項目の更新
        $user->user_name = $request->user_name;
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building_name = $request->building_name;
        $user->save();

        // マイリスト付きトップページにリダイレクト
        return redirect('/?tab=mylist')->with('status', 'プロフィールを更新しました');
    }

    // マイページ画面の表示
    public function mypageProfile(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab');

        if ($tab === 'buy') {
            // 購入した商品の一覧
            $items = $user->purchases()->with('listing')->get()->pluck('listing');
        } elseif ($tab === 'sell') {
            // 出品した商品の一覧
            $items = $user->listings;
        } else {
            // デフォルト表示（出品）
            $tab = 'sell';
            $items = $user->listings;
        }

        return view('user_profile', [
            'user' => $user,
            'items' => $items,
            'tab' => $tab,
        ]);
    }
}