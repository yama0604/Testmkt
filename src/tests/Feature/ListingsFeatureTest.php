<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use App\Models\Listing;

class ListingsFeatureTest extends TestCase
{
    /** @test */
    public function 商品出品画面から情報を送信すると正しく保存される()
    {
        // 事前準備：ユーザーとカテゴリが存在することを確認
        $user = User::find(2);
        $this->assertNotNull($user, 'ユーザーID2が存在しません');

        $category = Category::first();
        $this->assertNotNull($category, 'カテゴリが1件も存在しません');

        // public/storage/product_images にテスト画像を置いておくこと前提
        // 実際の画像ファイルのコピーを使う
        $fakePath = storage_path('app/public/dummy.jpg'); // 実ファイルの場所を指定
        $file = new UploadedFile(
            $fakePath,
            'dummy.jpg',
            'image/jpeg',
            null,
            true
        );

        // 入力データ
        $formData = [
            'product_image' => $file,
            'categories' => [$category->id],
            'status' => '1',
            'listing_name' => 'テスト出品商品',
            'brand_name' => 'テストブランド',
            'explanation' => 'これはテスト用の商品説明です。',
            'price' => '12345',
        ];

        // 実行
        $response = $this->actingAs($user)->post(route('items.sellInsert'), $formData);

        // 登録確認
        $this->assertDatabaseHas('listings', [
            'user_id' => $user->id,
            'listing_name' => 'テスト出品商品',
            'brand_name' => 'テストブランド',
            'explanation' => 'これはテスト用の商品説明です。',
            'price' => 12345,
            'status' => 1,
        ]);

        $listing = Listing::where('listing_name', 'テスト出品商品')->first();
        $this->assertNotNull($listing);

        $this->assertDatabaseHas('category_listing', [
            'listing_id' => $listing->id,
            'category_id' => $category->id,
        ]);

        $this->assertTrue(
            file_exists(storage_path('app/public/' . $listing->image)),
            '画像ファイルが保存されていません: ' . $listing->image
        );

        $response->assertRedirect('/mypage');
    }
}
