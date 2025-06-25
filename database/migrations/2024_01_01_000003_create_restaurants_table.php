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
        // Tabla de restaurantes - entidad principal del directorio
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            // name: nombre del restaurante, obligatorio
            $table->string('name');
            // description: descripción del restaurante
            $table->text('description')->nullable();
            // address: dirección física completa, obligatorio
            $table->string('address');
            // phone: número de teléfono de contacto
            $table->string('phone')->nullable();
            // email: correo de contacto del restaurante
            $table->string('email')->nullable();
            // website: sitio web del restaurante
            $table->string('website')->nullable();
            // opening_hours: horarios de atención en formato JSON
            $table->json('opening_hours')->nullable();
            // price_range: rango de precios (1-4, $ a $$$$)
            $table->tinyInteger('price_range')->default(2);
            // status: estado del restaurante (active, inactive, pending)
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            // latitude y longitude para mapas
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            // foreign: user_id -> users.id, cascade on delete
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Índices para búsquedas
            $table->index(['status', 'name']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};