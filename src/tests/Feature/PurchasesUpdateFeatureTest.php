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

class PurchasesUpdateFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }
        /** @test */
        public function 登録した住所が商品購入画面に正しく反映される()
        {
            $user = User::find(2);
            $item = Listing::find(4);
            $this->assertNotNull($user);
            $this->assertNotNull($item);
            $this->actingAs($user);
            // 送付先住所変更
            $updateResponse = $this->patch(route('purchase.address.update', ['item_id' => $item->id]), [
                'post_code' => '100-0001',
                'address' => '東京都港区六本木1-1',
                'building' => '仙石森ビル',
            ]);
            $updateResponse->assertRedirect(route('purchase', ['item' => $item->id]));
            // 商品購入画面での反映を確認
            $purchasePage = $this->get(route('purchase', ['item' => $item->id]));
            $purchasePage->assertSee('100-0001');
            $purchasePage->assertSee('東京都港区六本木1-1');
            $purchasePage->assertSee('仙石森ビル');
            // 購入処理実行
            $purchaseResponse = $this->post(route('purchase.complete', ['id' => $item->id]), [
                'payment'   => '1', // カード払い
                'post_code' => '100-0001',
                'address'   => '東東京都港区六本木1-1',
                'building_name' => '仙石森ビル',
            ]);
            // 購入レコードの確認
            $purchase = Purchase::where('user_id', $user->id)
                ->where('listing_id', $item->id)
                ->first();
            $this->assertNotNull($purchase);
            $this->assertEquals('100-0001', $purchase->shipping_post_code);
            $this->assertEquals('東京都港区六本木1-1', $purchase->shipping_address);
            $this->assertEquals('仙石森ビル', $purchase->shipping_building_name);
        }
    }