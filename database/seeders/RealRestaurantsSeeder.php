<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Commands\Seeders\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\User;
use App\Models\Photo;

class RealRestaurantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías existentes
        $italiana = Category::where('name', 'Italiana')->first();
        $espanola = Category::where('name', 'Española')->first();
        $japonesa = Category::where('name', 'Japonesa')->first();
        $mexicana = Category::where('name', 'Mexicana')->first();
        $vegetariana = Category::where('name', 'Vegetariana')->first();
        $mariscos = Category::where('name', 'Mariscos')->first();
        $asiatica = Category::where('name', 'Asiática')->first();
        $francesa = Category::where('name', 'Francesa')->first();

        // Obtener un usuario para asignar como propietario
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin Usuario',
                'email' => 'admin@restaurantes.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        $restaurants = [
            [
                'name' => 'El Celler de Can Roca',
                'description' => 'Restaurante de alta cocina catalana dirigido por los hermanos Roca. Tres estrellas Michelin y considerado uno de los mejores del mundo.',
                'address' => 'Carrer de Can Sunyer, 48, 17007 Girona',
                'phone' => '+34 972 22 21 57',
                'email' => 'info@cellercanroca.com',
                'website' => 'https://cellercanroca.com',
                'price_range' => 4,
                'opening_hours' => json_encode([
                    'lunes' => 'Cerrado',
                    'martes' => '19:30-00:00',
                    'miercoles' => '13:30-15:30,19:30-00:00',
                    'jueves' => '13:30-15:30,19:30-00:00',
                    'viernes' => '13:30-15:30,19:30-00:00',
                    'sabado' => '13:30-15:30,19:30-00:00',
                    'domingo' => 'Cerrado'
                ]),
                'categories' => [$espanola],
                'photos' => [
                    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'DiverXO',
                'description' => 'Restaurante de vanguardia del chef Dabiz Muñoz. Fusión creativa con influencias asiáticas. Tres estrellas Michelin.',
                'address' => 'Calle de Padre Damián, 23, 28036 Madrid',
                'phone' => '+34 915 70 07 66',
                'email' => 'reservas@diverxo.com',
                'website' => 'https://diverxo.com',
                'price_range' => 4,
                'opening_hours' => json_encode([
                    'lunes' => 'Cerrado',
                    'martes' => 'Cerrado',
                    'miercoles' => '20:00-00:00',
                    'jueves' => '20:00-00:00',
                    'viernes' => '20:00-00:00',
                    'sabado' => '20:00-00:00',
                    'domingo' => 'Cerrado'
                ]),
                'categories' => [$asiatica, $espanola],
                'photos' => [
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'Casa Botín',
                'description' => 'El restaurante más antiguo del mundo según el Libro Guinness de los Récords. Especialidad en cochinillo y cordero asado.',
                'address' => 'Calle de Cuchilleros, 17, 28005 Madrid',
                'phone' => '+34 913 66 42 17',
                'email' => 'info@botin.es',
                'website' => 'https://botin.es',
                'price_range' => 3,
                'opening_hours' => json_encode([
                    'lunes' => '13:00-16:00,20:00-00:00',
                    'martes' => '13:00-16:00,20:00-00:00',
                    'miercoles' => '13:00-16:00,20:00-00:00',
                    'jueves' => '13:00-16:00,20:00-00:00',
                    'viernes' => '13:00-16:00,20:00-00:00',
                    'sabado' => '13:00-16:00,20:00-00:00',
                    'domingo' => '13:00-16:00,20:00-00:00'
                ]),
                'categories' => [$espanola],
                'photos' => [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'Kabuki Wellington',
                'description' => 'Restaurante japonés de alta cocina con una estrella Michelin. Fusión nikkei con toques mediterráneos.',
                'address' => 'Calle de Velázquez, 6, 28001 Madrid',
                'phone' => '+34 914 17 64 15',
                'email' => 'wellington@grupokabuki.com',
                'website' => 'https://grupokabuki.com',
                'price_range' => 4,
                'opening_hours' => json_encode([
                    'lunes' => '13:30-15:30,20:30-23:30',
                    'martes' => '13:30-15:30,20:30-23:30',
                    'miercoles' => '13:30-15:30,20:30-23:30',
                    'jueves' => '13:30-15:30,20:30-23:30',
                    'viernes' => '13:30-15:30,20:30-23:30',
                    'sabado' => '13:30-15:30,20:30-23:30',
                    'domingo' => 'Cerrado'
                ]),
                'categories' => [$japonesa],
                'photos' => [
                    'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1553621042-f6e147245754?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'La Pepica',
                'description' => 'Restaurante tradicional valenciano famoso por su paella. Fundado en 1898, fue frecuentado por Ernest Hemingway.',
                'address' => 'Passeig de Neptú, 6, 46011 Valencia',
                'phone' => '+34 963 71 03 66',
                'email' => 'info@lapepica.com',
                'website' => 'https://lapepica.com',
                'price_range' => 3,
                'opening_hours' => json_encode([
                    'lunes' => '13:00-15:30,20:00-23:30',
                    'martes' => '13:00-15:30,20:00-23:30',
                    'miercoles' => '13:00-15:30,20:00-23:30',
                    'jueves' => '13:00-15:30,20:00-23:30',
                    'viernes' => '13:00-15:30,20:00-23:30',
                    'sabado' => '13:00-15:30,20:00-23:30',
                    'domingo' => '13:00-15:30,20:00-23:30'
                ]),
                'categories' => [$espanola, $mariscos],
                'photos' => [
                    'https://images.unsplash.com/photo-1534080564583-6be75777b70a?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'Osteria Francescana',
                'description' => 'Auténtica cocina italiana en el corazón de Barcelona. Pasta fresca hecha a mano y ingredientes importados de Italia.',
                'address' => 'Carrer de Muntaner, 171, 08036 Barcelona',
                'phone' => '+34 934 30 90 20',
                'email' => 'info@osteriafrancescana.es',
                'website' => 'https://osteriafrancescana.es',
                'price_range' => 3,
                'opening_hours' => json_encode([
                    'lunes' => 'Cerrado',
                    'martes' => '19:00-23:30',
                    'miercoles' => '13:00-15:30,19:00-23:30',
                    'jueves' => '13:00-15:30,19:00-23:30',
                    'viernes' => '13:00-15:30,19:00-23:30',
                    'sabado' => '13:00-15:30,19:00-23:30',
                    'domingo' => '13:00-15:30,19:00-23:30'
                ]),
                'categories' => [$italiana],
                'photos' => [
                    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'Green Spot',
                'description' => 'Restaurante vegetariano y vegano con opciones creativas y saludables. Ingredientes orgánicos y de temporada.',
                'address' => 'Carrer de la Reina Cristina, 12, 08003 Barcelona',
                'phone' => '+34 932 20 15 65',
                'email' => 'hola@greenspot.es',
                'website' => 'https://greenspot.es',
                'price_range' => 3,
                'opening_hours' => json_encode([
                    'lunes' => '12:00-16:00,19:00-23:00',
                    'martes' => '12:00-16:00,19:00-23:00',
                    'miercoles' => '12:00-16:00,19:00-23:00',
                    'jueves' => '12:00-16:00,19:00-23:00',
                    'viernes' => '12:00-16:00,19:00-23:00',
                    'sabado' => '12:00-16:00,19:00-23:00',
                    'domingo' => '12:00-16:00,19:00-23:00'
                ]),
                'categories' => [$vegetariana],
                'photos' => [
                    'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&h=600&fit=crop'
                ]
            ],
            [
                'name' => 'Taco Libre',
                'description' => 'Auténtica comida mexicana con recetas tradicionales. Tacos, burritos y margaritas en ambiente festivo.',
                'address' => 'Calle de Fuencarral, 108, 28004 Madrid',
                'phone' => '+34 915 21 87 40',
                'email' => 'info@tacolibre.es',
                'website' => 'https://tacolibre.es',
                'price_range' => 2,
                'opening_hours' => json_encode([
                    'lunes' => '18:00-01:00',
                    'martes' => '18:00-01:00',
                    'miercoles' => '18:00-01:00',
                    'jueves' => '18:00-01:00',
                    'viernes' => '18:00-02:00',
                    'sabado' => '13:00-02:00',
                    'domingo' => '13:00-01:00'
                ]),
                'categories' => [$mexicana],
                'photos' => [
                    'https://images.unsplash.com/photo-1565299585323-38174c4a6471?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=800&h=600&fit=crop'
                ]
            ]
        ];

        foreach ($restaurants as $restaurantData) {
            $restaurant = Restaurant::create([
                'name' => $restaurantData['name'],
                'description' => $restaurantData['description'],
                'address' => $restaurantData['address'],
                'phone' => $restaurantData['phone'],
                'email' => $restaurantData['email'],
                'website' => $restaurantData['website'],
                'price_range' => $restaurantData['price_range'],
                'opening_hours' => $restaurantData['opening_hours'],
                'status' => 'approved',
                'user_id' => $user->id,
            ]);

            // Asignar categorías
            if (isset($restaurantData['categories'])) {
                foreach ($restaurantData['categories'] as $category) {
                    if ($category) {
                        $restaurant->categories()->attach($category->id);
                    }
                }
            }

            // Crear fotos
            if (isset($restaurantData['photos'])) {
                foreach ($restaurantData['photos'] as $index => $photoUrl) {
                    Photo::create([
                        'url' => $photoUrl,
                        'alt_text' => "Foto de {$restaurant->name}",
                        'is_primary' => $index === 0,
                        'order' => $index + 1,
                        'imageable_type' => Restaurant::class,
                        'imageable_id' => $restaurant->id,
                    ]);
                }
            }
        }
    }
}
