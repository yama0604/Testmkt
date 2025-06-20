<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\Category;
use App\Models\CategoryListing;

class CategoryListingTableSeeder extends Seeder
{
    public function run(): void
    {
        $listings = \App\Models\Listing::all();
        $categories = \App\Models\Category::all();

        if ($listings->isEmpty() || $categories->isEmpty()) {
            return;
        }

        foreach ($listings as $listing) {
            // 1〜3カテゴリをランダムに選択
            $selectedCategories = $categories->random(rand(1, 3));

            foreach ($selectedCategories as $category) {
                CategoryListing::firstOrCreate([
                    'listing_id'  => $listing->id,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
