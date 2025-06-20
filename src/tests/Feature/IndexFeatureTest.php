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

class IndexFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function すべての商品が表示される()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('HDD');
        $response->assertSee('玉ねぎ3束');
        $response->assertSee('革靴');
        $response->assertSee('ノートPC');
        $response->assertSee('マイク');
        $response->assertSee('ショルダーバッグ');
        $response->assertSee('タンブラー');
        $response->assertSee('コーヒーミル');
        $response->assertSee('メイクセット');
    }

    /** @test */
    public function 購入済み商品に「Sold」のラベルが表示される()
    {
        $listing = \App\Models\Listing::find(1);
        // sold_flag を確認
        $this->assertEquals(\App\Models\Listing::SOLD_FLAG_SOLD_OUT, $listing->sold_flag);
        // 商品一覧画面取得
        $response = $this->get('/');
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
        $response = $this->actingAs($user)->get('/');
        $response->assertDontSee('腕時計');
    }

    /** @test */
    public function ログアウト処理が実行される()
    {
        $user = \App\Models\User::find(1);
        $response = $this->actingAs($user)->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
