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
        // Tabla de reseñas para que los usuarios valoren restaurantes
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // rating: calificación entre 1 y 5 estrellas, obligatorio
            $table->tinyInteger('rating')->unsigned();
            // comment: comentario opcional del usuario
            $table->text('comment')->nullable();
            // foreign: user_id -> users.id, cascade on delete
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // foreign: restaurant_id -> restaurants.id, cascade on delete
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Un usuario solo puede reseñar un restaurante una vez
            $table->unique(['user_id', 'restaurant_id']);
            // Índice para búsquedas por rating
            $table->index(['restaurant_id', 'rating']);
            
            // Validación: rating debe estar entre 1 y 5
            $table->check('rating >= 1 AND rating <= 5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};