<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Restaurant;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = Restaurant::all();
        $users = User::where('role', 'user')->get();
        
        if ($users->isEmpty()) {
            $users = User::take(5)->get();
        }

        $comments = [
            'Excelente comida y servicio. Muy recomendado.',
            'Buena experiencia, volveré pronto.',
            'La comida estaba deliciosa, ambiente muy agradable.',
            'Servicio rápido y comida fresca.',
            'Precios justos y buena calidad.',
            'Lugar perfecto para una cena romántica.',
            'Comida casera de excelente calidad.',
            'Personal muy amable y atento.',
            'Ambiente familiar y acogedor.',
            'Una experiencia gastronómica única.',
        ];

        foreach ($restaurants as $restaurant) {
            // Create 3-8 reviews per restaurant
            $reviewCount = rand(3, 8);
            $selectedUsers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($selectedUsers as $user) {
                // Use firstOrCreate to prevent duplicate reviews from same user for same restaurant
                Review::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'restaurant_id' => $restaurant->id,
                    ],
                    [
                        'rating' => rand(3, 5), // Ratings between 3-5 stars
                        'comment' => $comments[array_rand($comments)],
                    ]
                );
            }
        }

        $this->command->info('Reviews seeded successfully!');
    }
}