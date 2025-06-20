<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\CategoryListing;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Listing;
use App\Models\Purchase;
use App\Models\User;

class UserProfileFeatureTest extends TestCase
{
    /** @test */
    public function プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧が正しく表示される()
    {
        // ユーザーID2を取得
        $user = User::find(2);
        $this->assertNotNull($user);
        // 出品商品
        $sellItem = Listing::find(11);
        $this->assertNotNull($sellItem);
        $this->assertEquals($user->id, $sellItem->user_id);
        // 購入商品
        $purchase = Purchase::find(1);
        $this->assertNotNull($purchase);
        $this->assertEquals($user->id, $purchase->user_id);
        $buyItem = $purchase->listing;
        $this->assertNotNull($buyItem);
        // ログイン
        $this->actingAs($user);
        // 出品タブ表示確認
        $sellTab = $this->get('/mypage?tab=sell');
        $sellTab->assertSee($user->user_name);
        $sellTab->assertSee($sellItem->listing_name);
        $sellTab->assertSee($user->image ?? 'default-user.png');
        // 購入タブ表示確認
        $buyTab = $this->get('/mypage?tab=buy');
        $buyTab->assertSee($buyItem->listing_name);
    }
}
