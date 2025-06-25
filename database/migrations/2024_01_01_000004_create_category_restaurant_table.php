<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla pivote para relación muchos a muchos entre restaurantes y categorías
        // Un restaurante puede tener múltiples categorías (ej: italiana y pizza)
        Schema::create('category_restaurant', function (Blueprint $table) {
            $table->id();
            // foreign: category_id -> categories.id, cascade on delete
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            // foreign: restaurant_id -> restaurants.id, cascade on delete
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados: un restaurante no puede tener la misma categoría dos veces
            $table->unique(['category_id', 'restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_restaurant');
    }
};