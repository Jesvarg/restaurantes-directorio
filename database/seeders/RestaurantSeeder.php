<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\User;
use App\Models\Photo;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $owners = User::where('role', 'owner')->get();
        
        if ($owners->isEmpty()) {
            $owners = User::take(3)->get();
        }

        $restaurants = [
            [
                'name' => 'La Parrilla Dorada',
                'description' => 'Especialistas en carnes a la parrilla con más de 20 años de experiencia. Ambiente familiar y acogedor.',
                'address' => 'Av. Principal 123, Centro',
                'phone' => '+1234567890',
                'email' => 'info@parrilladorada.com',
                'website' => 'https://parrilladorada.com',
                'opening_hours' => json_encode(['monday' => '12:00-23:00', 'tuesday' => '12:00-23:00', 'wednesday' => '12:00-23:00', 'thursday' => '12:00-23:00', 'friday' => '12:00-23:00', 'saturday' => '12:00-23:00', 'sunday' => '12:00-23:00']),
                'price_range' => 2,
                'latitude' => 19.4326,
                'longitude' => -99.1332,
                'status' => 'active',
            ],
            [
                'name' => 'Sushi Zen',
                'description' => 'Auténtica cocina japonesa con ingredientes frescos importados. Experiencia gastronómica única.',
                'address' => 'Calle Sakura 456, Zona Rosa',
                'phone' => '+1234567891',
                'email' => 'contacto@sushizen.com',
                'website' => 'https://sushizen.com',
                'opening_hours' => json_encode(['tuesday' => '18:00-24:00', 'wednesday' => '18:00-24:00', 'thursday' => '18:00-24:00', 'friday' => '18:00-24:00', 'saturday' => '18:00-24:00', 'sunday' => '18:00-24:00']),
                'price_range' => 4,
                'latitude' => 19.4284,
                'longitude' => -99.1276,
                'status' => 'active',
            ],
            [
                'name' => 'Pizzería Napoli',
                'description' => 'Pizza artesanal al horno de leña con recetas tradicionales italianas. Masa fermentada 48 horas.',
                'address' => 'Via Roma 789, Colonia Italia',
                'phone' => '+1234567892',
                'email' => 'ciao@pizzerianapoli.com',
                'website' => 'https://pizzerianapoli.com',
                'opening_hours' => json_encode(['monday' => '13:00-23:30', 'tuesday' => '13:00-23:30', 'wednesday' => '13:00-23:30', 'thursday' => '13:00-23:30', 'friday' => '13:00-23:30', 'saturday' => '13:00-23:30', 'sunday' => '13:00-23:30']),
                'price_range' => 2,
                'latitude' => 19.4247,
                'longitude' => -99.1353,
                'status' => 'active',
            ],
            [
                'name' => 'Tacos El Güero',
                'description' => 'Los mejores tacos de la ciudad con tortillas hechas a mano y salsas secretas de la abuela.',
                'address' => 'Mercado Central Local 15',
                'phone' => '+1234567893',
                'email' => 'pedidos@tacoselguero.com',
                'opening_hours' => json_encode(['monday' => '08:00-22:00', 'tuesday' => '08:00-22:00', 'wednesday' => '08:00-22:00', 'thursday' => '08:00-22:00', 'friday' => '08:00-22:00', 'saturday' => '08:00-22:00']),
                'price_range' => 1,
                'latitude' => 19.4355,
                'longitude' => -99.1405,
                'status' => 'active',
            ],
            [
                'name' => 'Café Literario',
                'description' => 'Café de especialidad con ambiente bohemio. Perfecto para trabajar o leer un buen libro.',
                'address' => 'Calle de los Libros 321, Centro Histórico',
                'phone' => '+1234567894',
                'email' => 'hola@cafeliterario.com',
                'website' => 'https://cafeliterario.com',
                'opening_hours' => json_encode(['monday' => '07:00-22:00', 'tuesday' => '07:00-22:00', 'wednesday' => '07:00-22:00', 'thursday' => '07:00-22:00', 'friday' => '07:00-22:00', 'saturday' => '07:00-22:00', 'sunday' => '07:00-22:00']),
                'price_range' => 1,
                'latitude' => 19.4338,
                'longitude' => -99.1370,
                'status' => 'active',
            ],
            [
                'name' => 'Mariscos La Perla',
                'description' => 'Mariscos frescos del día con preparaciones tradicionales del Pacífico mexicano.',
                'address' => 'Malecón 567, Puerto Nuevo',
                'phone' => '+1234567895',
                'email' => 'ventas@mariscoslaperla.com',
                'opening_hours' => json_encode(['wednesday' => '12:00-21:00', 'thursday' => '12:00-21:00', 'friday' => '12:00-21:00', 'saturday' => '12:00-21:00', 'sunday' => '12:00-21:00']),
                'price_range' => 2,
                'latitude' => 19.4290,
                'longitude' => -99.1420,
                'status' => 'active',
            ],
        ];

        foreach ($restaurants as $index => $restaurantData) {
            // Assign owner
            $restaurantData['user_id'] = $owners[$index % $owners->count()]->id;
            
            // Use firstOrCreate to prevent duplicates
            $restaurant = Restaurant::firstOrCreate(
                ['name' => $restaurantData['name']],
                $restaurantData
            );
            
            // Only attach categories and photos if restaurant was just created
            if ($restaurant->wasRecentlyCreated) {
                // Attach random categories
                $randomCategories = $categories->random(rand(1, 3));
                $restaurant->categories()->attach($randomCategories->pluck('id'));
                
                // Add sample photos
                $samplePhotos = [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&h=600&fit=crop'
                ];
                
                // Create 2-3 photos per restaurant
                $numPhotos = rand(2, 3);
                for ($i = 0; $i < $numPhotos; $i++) {
                    $restaurant->photos()->create([
                        'url' => $samplePhotos[($index + $i) % count($samplePhotos)],
                        'alt_text' => $restaurant->name . ' - Imagen ' . ($i + 1),
                        'is_primary' => $i === 0, // Primera foto como principal
                        'order' => $i + 1
                    ]);
                }
            }
        }

        $this->command->info('Restaurants seeded successfully!');
    }
}