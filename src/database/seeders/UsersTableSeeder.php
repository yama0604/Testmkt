<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'user_name' => 'testuser',
            'password' => bcrypt('password'),
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'post_code' => '123-4567',
            'address' => '東京都品川区西品川1-2-3',
            'building_name' => 'テストビル777',
            'image' => 'dummy.jpg',
        ]);

        User::create([
            'user_name' => 'yama',
            'password' => bcrypt('password'),
            'email' => 'yama@example.com',
            'email_verified_at' => now(),
            'post_code' => '999-8888',
            'address' => '東京都港区三田1-2-3',
            'building_name' => '港ビル777',
            'image' => 'dummy.jpg',
        ]);
    }
}
