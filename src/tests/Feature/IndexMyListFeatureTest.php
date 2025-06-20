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

class IndexMyListFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function いいねをした商品が表示される()
    {
        $user = \App\Models\User::find(2);
    // いいねしたlistingを取得
    $likeListings = Like::where('user_id', $user->id)
    ->with('listing')
    ->get()
    ->pluck('listing');
    $response = $this->actingAs($user)->get('/?tab=mylist');
    // 各商品名が表示されていることを確認
    foreach ($likeListings as $listing) {
    $response->assertSee($listing->listing_name);
    }
    // 最低1件以上likeがあることを確認
    $this->assertGreaterThan(0, $likeListings->count(), 'このユーザーにはLikeがありません');
    }

    /** @test */
    public function 購入済み商品に「Sold」のラベルが表示される()
    {
        $user = \App\Models\User::find(2);
        $this->actingAs($user);
        $listing = \App\Models\Listing::find(1);
        // sold_flag を確認
        $this->assertEquals(\App\Models\Listing::SOLD_FLAG_SOLD_OUT, $listing->sold_flag);
        // 商品一覧画面取得
        $response = $this->get('/?tab=mylist');
        // 商品名
        $response->assertSee($listing->listing_name);
        // SOLD ラベルが表示されていること
        $response->assertSeeInOrder([
            $listing->listing_name,
            'SOLD'
        ]);
    }

    /** @test */
    public function 自分が出品した商品が一覧に表示されない()
    {
        $user = \App\Models\User::find(1);
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertDontSee('腕時計');
    }

    /** @test */
    public function 何も表示されない()
    {
        $response = $this->get('/?tab=mylist');
        $response->assertRedirect('/');
    }

    /** @test */
    public function 部分一致する商品の表示および検索キーワードが保持されていること()
    {
        $user = \App\Models\User::find(2);
        $this->actingAs($user);
        $keyword = '時計';
        // 検索付きでおすすめタブにアクセス
        $responseRecommend = $this->get('/?keyword=' . urlencode($keyword));
        $responseRecommend->assertSee($keyword);
        // 同じキーワード付きでマイリストにアクセス
        $responseMyList = $this->get('/?tab=mylist&keyword=' . urlencode($keyword));
        $responseMyList->assertStatus(200);
        $responseMyList->assertSee($keyword);
        // 時計が商品名に含まれていることを確認
        $responseMyList->assertSee('時計');
    }


}
