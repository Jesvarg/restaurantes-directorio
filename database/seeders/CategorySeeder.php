<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Italiana',
                'description' => 'Auténtica cocina italiana con pastas, pizzas y platos tradicionales',
                'slug' => 'italiana'
            ],
            [
                'name' => 'Mexicana',
                'description' => 'Sabores tradicionales mexicanos con ingredientes frescos y especias auténticas',
                'slug' => 'mexicana'
            ],
            [
                'name' => 'Asiática',
                'description' => 'Cocina asiática variada incluyendo china, japonesa, tailandesa y más',
                'slug' => 'asiatica'
            ],
            [
                'name' => 'Americana',
                'description' => 'Clásicos americanos como hamburguesas, steaks y comfort food',
                'slug' => 'americana'
            ],
            [
                'name' => 'Mediterránea',
                'description' => 'Cocina mediterránea saludable con aceite de oliva, pescados y vegetales frescos',
                'slug' => 'mediterranea'
            ],
            [
                'name' => 'Francesa',
                'description' => 'Elegante cocina francesa con técnicas refinadas y sabores sofisticados',
                'slug' => 'francesa'
            ],
            [
                'name' => 'India',
                'description' => 'Especias aromáticas y sabores intensos de la cocina india tradicional',
                'slug' => 'india'
            ],
            [
                'name' => 'Vegetariana',
                'description' => 'Opciones completamente vegetarianas con ingredientes frescos y naturales',
                'slug' => 'vegetariana'
            ],
            [
                'name' => 'Vegana',
                'description' => 'Cocina 100% vegana sin productos de origen animal',
                'slug' => 'vegana'
            ],
            [
                'name' => 'Mariscos',
                'description' => 'Especialidades del mar con pescados y mariscos frescos',
                'slug' => 'mariscos'
            ],
            [
                'name' => 'Parrilla',
                'description' => 'Carnes a la parrilla y barbacoa con sabores ahumados',
                'slug' => 'parrilla'
            ],
            [
                'name' => 'Postres',
                'description' => 'Dulces, pasteles y postres artesanales para endulzar tu día',
                'slug' => 'postres'
            ],
            [
                'name' => 'Café',
                'description' => 'Cafeterías especializadas en café de calidad y bebidas calientes',
                'slug' => 'cafe'
            ],
            [
                'name' => 'Bar',
                'description' => 'Bares y pubs con cocteles, cervezas y ambiente relajado',
                'slug' => 'bar'
            ],
            [
                'name' => 'Comida Rápida',
                'description' => 'Opciones rápidas y convenientes para comer sobre la marcha',
                'slug' => 'comida-rapida'
            ],
            [
                'name' => 'Gourmet',
                'description' => 'Alta cocina con ingredientes premium y presentación elegante',
                'slug' => 'gourmet'
            ],
            [
                'name' => 'Familiar',
                'description' => 'Restaurantes familiares con ambiente acogedor y menús para todos',
                'slug' => 'familiar'
            ],
            [
                'name' => 'Brunch',
                'description' => 'Especialistas en brunch con opciones para desayuno y almuerzo',
                'slug' => 'brunch'
            ],
            [
                'name' => 'Fusión',
                'description' => 'Cocina fusión que combina diferentes tradiciones culinarias',
                'slug' => 'fusion'
            ],
            [
                'name' => 'Saludable',
                'description' => 'Opciones saludables con ingredientes orgánicos y nutritivos',
                'slug' => 'saludable'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description']
                ]
            );
        }

        $this->command->info('Categories seeded successfully!');
    }
}