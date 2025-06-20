<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Listing;

class PurchasesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::find(2);
        $listing = \App\Models\Listing::find(1);

        if ($user && $listing) {
            Purchase::create([
                'user_id' => $user->id,
                'listing_id' => $listing->id,
                'payment' => Purchase::PAYMENT_CONVENIENCE,
                'shipping_post_code' => '111-2222',
                'shipping_address' => '神奈川県横浜市関内1-1-1',
                'shipping_building_name' => '関内タワー10F',
            ]);
        }
    }
}
