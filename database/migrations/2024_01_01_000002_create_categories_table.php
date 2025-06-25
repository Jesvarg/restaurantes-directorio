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
        // Tabla de categorías para clasificar restaurantes (italiana, mexicana, etc.)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // name: nombre de la categoría, único y obligatorio
            $table->string('name')->unique();
            // description: descripción opcional de la categoría
            $table->text('description')->nullable();
            // slug: versión URL-friendly del nombre para SEO
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};