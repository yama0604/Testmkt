<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Listing;


class CommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::find(2);
        $listing1 = \App\Models\Listing::find(1);
        $listing2 = \App\Models\Listing::find(2);

        if ($user) {
            if ($listing1) {
                Comment::create([
                    'user_id' => $user->id,
                    'listing_id' => $listing1->id,
                    'comment' => 'いいですね！',
                ]);
            }

            if ($listing2) {
                Comment::create([
                    'user_id' => $user->id,
                    'listing_id' => $listing2->id,
                    'comment' => '魅力的です！',
                ]);
            }
        }
    }
}