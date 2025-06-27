<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order: Categories -> Users -> Restaurants -> Reviews
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            RestaurantSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
