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

class RegisterFeatureTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function 「お名前を入力してください」が表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test2@example.com',
            'password' => 'abcdef12',
            'confirm_password' => 'abcdef12',
        ]);

        $this->assertContains('お名前を入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「メールアドレスを入力してください」が表示される()
    {
        $response = $this->post('/register', [
            'name' => '高知テック',
            'email' => '',
            'password' => 'abcdef12',
            'confirm_password' => 'abcdef12',
        ]);

        $this->assertContains('メールアドレスを入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「パスワードを入力してください」が表示される()
    {
        $response = $this->post('/register', [
            'name' => '高知テック',
            'email' => 'test2@example.com',
            'password' => '',
            'confirm_password' => '',
        ]);

        $this->assertContains('パスワードを入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「パスワードは8文字以上で入力してください」が表示される()
    {
        $response = $this->post('/register', [
            'name' => '高知テック',
            'email' => 'test2@example.com',
            'password' => 'abcdef1',
            'confirm_password' => 'abcdef1',
        ]);

        $this->assertContains('パスワードは8文字以上で入力してください。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 「パスワードと一致しません」が表示される()
    {
        $response = $this->post('/register', [
            'name' => '高知テック',
            'email' => 'test2@example.com',
            'password' => 'abcdef12',
            'confirm_password' => 'abcdef13',
        ]);

        $this->assertContains('パスワードと一致しません。', $response->getSession()->get('errors')->all());
        dump($response->getSession()->get('errors')->all());
    }

    /** @test */
    public function 正常に会員情報が登録される()
    {
        $response = $this->post('/register', [
            'name' => '高知テック',
            'email' => 'test2@example.com',
            'password' => 'abcdef12',
            'confirm_password' => 'abcdef12',
        ]);

        $this->assertNull($response->getSession()->get('errors'));
        $this->assertAuthenticated();
    }
}
