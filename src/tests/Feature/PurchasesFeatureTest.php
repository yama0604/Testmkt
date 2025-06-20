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

class PurchasesFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function 購入が完了する、購入した商品が「sold」として表示されている、購入した商品がプロフィールの購入した商品一覧に追加されている()
    {
        $user = \App\Models\User::find(2);
        $item = \App\Models\Listing::find(3);
        $this->assertNotNull($user);
        $this->assertNotNull($item);
        $this->assertEquals(0, $item->sold_flag);
        $selectedPayment = '1';
        // 購入処理を実行
        $response = $this->actingAs($user)->post(route('purchase.complete', ['id' => $item->id]), [
            'payment'   => $selectedPayment,
            'post_code' => '999-8888',
            'address' => '東京都港区三田1-2-3',
            'building_name' => '港ビル777',
        ]);
        // purchasesテーブルにレコードが存在し、支払い方法が正しいことを確認
        $purchase = \App\Models\Purchase::where('user_id', $user->id)
                    ->where('listing_id', $item->id)
                    ->first();

        $this->assertNotNull($purchase, 'purchasesテーブルに購入記録がない');
        $this->assertEquals($selectedPayment, $purchase->payment, '支払い方法が保存されていない');

        // 再度DBから最新情報取得
        $item = $item->fresh();
        $this->assertEquals(1, $item->sold_flag);
        $response = $this->actingAs($user)->get('/');
        $response->assertSee($item->listing_name);
        $response->assertSee('SOLD');
        $mypageResponse = $this->actingAs($user)->get('/mypage?tab=buy');
        $mypageResponse->assertSee($item->listing_name);
    }
}