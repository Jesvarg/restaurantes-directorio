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
        // Tabla pivote para favoritos - relación muchos a muchos entre usuarios y restaurantes
        // Permite a los usuarios marcar restaurantes como favoritos
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            // foreign: user_id -> users.id, cascade on delete
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // foreign: restaurant_id -> restaurants.id, cascade on delete
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Clave primaria compuesta: un usuario no puede marcar el mismo restaurante como favorito dos veces
            $table->unique(['user_id', 'restaurant_id']);
            // Índice para búsquedas rápidas por usuario
            $table->index('user_id');
            // Índice para búsquedas rápidas por restaurante
            $table->index('restaurant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};