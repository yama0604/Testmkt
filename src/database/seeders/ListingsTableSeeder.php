<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;

class ListingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = \App\Models\User::first()->id;

        $user1Id = User::find(1);
        $user2Id = User::find(2);
        $listings = [
            [
                'user_id' => 1,
                'listing_name' => '腕時計',
                'brand_name' => '時計屋',
                'price' => 15000,
                'explanation' => 'スタイリッシュなデザインのメンズ腕時計',
                'status' => 0,
                'image' => 'watch.jpg',
                'sold_flag' => 1,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'HDD',
                'brand_name' => '電気屋',
                'price' => 5000,
                'explanation' => '高速で信頼性の高いハードディスク',
                'status' => 1,
                'image' => 'hdd.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => '玉ねぎ3束',
                'brand_name' => '農家',
                'price' => 300,
                'explanation' => '新鮮な玉ねぎ3束のセット',
                'status' => 2,
                'image' => 'onions.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => '革靴',
                'brand_name' => '靴屋',
                'price' => 4000,
                'explanation' => 'クラシックなデザインの革靴',
                'status' => 3,
                'image' => 'leathershoes.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'ノートPC',
                'brand_name' => '電気屋',
                'price' => 45000,
                'explanation' => '高性能なノートパソコン',
                'status' => 0,
                'image' => 'laptop.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'マイク',
                'brand_name' => '電気屋',
                'price' => 8000,
                'explanation' => '高音質のレコーディング用マイク',
                'status' => 1,
                'image' => 'mic.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'ショルダーバッグ',
                'brand_name' => '鞄屋',
                'price' => 3500,
                'explanation' => 'おしゃれなショルダーバッグ',
                'status' => 2,
                'image' => 'shoulderbag.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'タンブラー',
                'brand_name' => '雑貨屋',
                'price' => 500,
                'explanation' => '使いやすいタンブラー',
                'status' => 3,
                'image' => 'tumbler.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'コーヒーミル',
                'brand_name' => '雑貨屋',
                'price' => 4000,
                'explanation' => '手動のコーヒーミル',
                'status' => 0,
                'image' => 'coffeemill.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 1,
                'listing_name' => 'メイクセット',
                'brand_name' => '化粧品屋',
                'price' => 2500,
                'explanation' => '便利なメイクアップセット',
                'status' => 1,
                'image' => 'makeset.jpg',
                'sold_flag' => 0,
            ],
            [
                'user_id' => 2,
                'listing_name' => 'すごいコーヒーミル',
                'brand_name' => 'ブラジルコーヒー',
                'price' => 99999,
                'explanation' => '高品質コーヒーミル',
                'status' => 0,
                'image' => 'coffeemill.jpg',
                'sold_flag' => 0,
            ],
        ];
        foreach ($listings as $listing) {
            Listing::create($listing);
        }
    }
}
