<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Listing;

class LikesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::find(2);
        $listing1 = \App\Models\Listing::find(1);
        $listing2 = \App\Models\Listing::find(2);

        if ($user) {
            if ($listing1) {
                Like::create([
                    'user_id' => $user->id,
                    'listing_id' => $listing1->id,
                ]);
            }

            if ($listing2) {
                Like::create([
                    'user_id' => $user->id,
                    'listing_id' => $listing2->id,
                ]);
            }
        }
    }
}