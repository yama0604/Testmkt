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

class ListingsShowFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function すべての情報が商品詳細ページに表示されている()
    {
        $item = \App\Models\Listing::find(2);
        // 商品ID 2 の存在チェック
        $this->assertNotNull($item, 'Listing ID 2 が存在しません');
        // 商品ID 2 がに2つ以上のカテゴリかチェック
        $this->assertTrue($item->categories()->exists(), 'カテゴリが紐づいていません');
        $response = $this->get(route('items.show', ['item' => $item->id]));
        $response->assertStatus(200);
        // 商品画像
        if ($item->image) {
            $response->assertSee('<img src="' . asset('storage/' . $item->image) . '"', false);
        }
        // 商品基本情報
        $response->assertSee($item->listing_name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->explanation);
        $response->assertSee($item->status_label);
        // 商品に紐づいているすべてのカテゴリ名が表示されているか確認
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
        // いいね数
        if ($item->likes->count() > 0) {
            $response->assertSee('<span class="count">' . $item->likes->count() . '</span>', false);
        }
        // コメント数表示
        if ($item->comments->count() > 0) {
            $response->assertSee('<span class="count">' . $item->comments->count() . '</span>', false);
        }
        // コメントが１件以上あることを確認
        $this->assertTrue($item->comments()->exists(), 'コメントが存在しません');
        $comment = $item->comments()->first();
        $response->assertSee($comment->comment);
        $response->assertSee($comment->user->user_name);
    }

    /** @test */
    public function いいねが解除され、いいね合計値が減少表示される、いいねした商品として登録され、いいね合計値が増加表示される、いいねアイコンが押下された状態では色が変化する()
    {
        $user = \App\Models\User::find(2);
        $item = \App\Models\Listing::find(2);
        $this->assertNotNull($user);
        $this->assertNotNull($item);
        // 初期状態（いいね済み）
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'listing_id' => $item->id,
        ]);
        $initialCount = $item->likes()->count();
        // いいね解除
        $this->actingAs($user)->post(route('items.like', ['id' => $item->id]));
        // DB確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'listing_id' => $item->id,
        ]);
        // 表示確認（減少）
        $response = $this->actingAs($user)->get(route('items.show', ['item' => $item->id]));
        $response->assertSee('<span class="count">' . ($initialCount - 1) . '</span>', false);
        $response->assertDontSee('icon--star active', false);
        // 再度いいね
        $this->actingAs($user)->post(route('items.like', ['id' => $item->id]));
        // DB確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'listing_id' => $item->id,
        ]);
        // 表示確認（再び増加 & activeクラス表示）
        $response = $this->actingAs($user)->get(route('items.show', ['item' => $item->id]));
        $response->assertSee('<span class="count">' . $initialCount . '</span>', false);
        $response->assertSee('icon--star active', false);
    }

    /** @test */
    public function コメントが保存され、コメント数が増加する()
    {
        $user = \App\Models\User::find(2);
        $item = \App\Models\Listing::find(3);
        $this->assertNotNull($user);
        $this->assertNotNull($item);
        // コメント送信前のコメント数
        $beforeCount = $item->comments()->count();
        // コメント送信
        $response = $this->actingAs($user)->post(route('comments.purchase', ['id' => $item->id]), [
            'comment' => 'これはテストコメントです。',
        ]);
        $response->assertRedirect();
        // DBに保存確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'listing_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);
        // コメント数の増加確認
        $afterCount = $item->fresh()->comments()->count();
        $this->assertSame($beforeCount + 1, $afterCount, 'コメント数増加なし');
    }

    /** @test */
    public function コメントが送信されない()
    {
        $item = \App\Models\Listing::find(3);
        $this->assertNotNull($item);
        $response = $this->post(route('comments.purchase', ['id' => $item->id]), [
            'comment' => '未ログインテスト',
        ]);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'listing_id' => $item->id,
            'comment' => '未ログインテスト',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = \App\Models\User::find(2);
        $item = \App\Models\Listing::find(3);
        $this->assertNotNull($user);
        $this->assertNotNull($item);
        $response = $this->actingAs($user)
            ->from(route('items.show', ['item' => $item->id]))
            ->post(route('comments.purchase', ['id' => $item->id]), [
                'comment' => '',
            ]);
        $this->assertContains('コメントを入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = \App\Models\User::find(2);
        $item = \App\Models\Listing::find(3);
        $this->assertNotNull($user);
        $this->assertNotNull($item);
        $longComment = str_repeat('あ', 256);
        $response = $this->actingAs($user)
            ->from(route('items.show', ['item' => $item->id]))
            ->post(route('comments.purchase', ['id' => $item->id]), [
                'comment' => $longComment,
            ]);
        $this->assertContains('コメントは255文字以内で入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }


}
