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

class LoginFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function 「メールアドレスを入力してください」が表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'abcdef12',
        ]);

        $this->assertContains('メールアドレスを入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「パスワードを入力してください」が表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => '',
        ]);

        $this->assertContains('パスワードを入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「ログイン情報が登録されていません」が表示される()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertContains('ログイン情報が登録されていません。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 正常にログイン処理が実行される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertNull($response->getSession()->get('errors'));
        $this->assertAuthenticated();
    }
}
