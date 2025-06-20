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

class UserProfileUpdateFeatureTest extends TestCase
{
    /** @test */
    public function 各項目の初期値が正しく表示されている()
    {
        $user = User::find(2);
        // 必須項目が設定されていることを事前確認
        $this->assertNotNull($user);
        $this->assertNotNull($user->user_name);
        $this->assertNotNull($user->post_code);
        $this->assertNotNull($user->address);
        // プロフィール編集画面を開く
        $response = $this->actingAs($user)->get(route('user.profile.edit'));
        $response->assertStatus(200);
        // 各フォームの初期値が反映されているか確認
        $response->assertSee('value="' . e($user->user_name) . '"', false);
        $response->assertSee('value="' . e($user->post_code) . '"', false);
        $response->assertSee('value="' . e($user->address) . '"', false);
        $response->assertSee('value="' . e($user->building_name) . '"', false);
        // プロフィール画像が表示されているか確認
        $expectedImage = $user->image ?? 'default-user.png';
        $response->assertSee('src="' . asset('storage/' . $expectedImage) . '"', false);
    }
}