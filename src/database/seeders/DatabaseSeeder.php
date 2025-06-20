<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ListingsTableSeeder::class,
            CategoriesTableSeeder::class,
            PurchasesTableSeeder::class,
            LikesTableSeeder::class,
            CommentsTableSeeder::class,
            CategoryListingTableSeeder::class,
        ]);
    }
}