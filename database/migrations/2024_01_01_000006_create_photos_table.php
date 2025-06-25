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
        // Tabla de fotos usando relación polimórfica
        // Permite asociar fotos a diferentes modelos (restaurantes, usuarios, etc.)
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            // url: ruta o URL de la imagen, obligatorio
            $table->string('url');
            // alt_text: texto alternativo para accesibilidad
            $table->string('alt_text')->nullable();
            // is_primary: indica si es la foto principal del modelo
            $table->boolean('is_primary')->default(false);
            // order: orden de visualización de las fotos
            $table->integer('order')->default(0);
            // Campos polimórficos: imageable_id e imageable_type
            // imageable_id: ID del modelo relacionado
            $table->unsignedBigInteger('imageable_id');
            // imageable_type: clase del modelo relacionado (App\Models\Restaurant, etc.)
            $table->string('imageable_type');
            $table->timestamps();
            
            // Índice compuesto para relación polimórfica
            $table->index(['imageable_type', 'imageable_id']);
            // Índice para foto principal
            $table->index(['imageable_type', 'imageable_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};