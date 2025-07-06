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
        Schema::create('restaurant_rejection_reasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            
            // Campos de validación como checks booleanos
            $table->boolean('name_invalid')->default(false);
            $table->boolean('description_invalid')->default(false);
            $table->boolean('address_invalid')->default(false);
            $table->boolean('phone_invalid')->default(false);
            $table->boolean('email_invalid')->default(false);
            $table->boolean('categories_missing')->default(false);
            $table->boolean('photos_missing')->default(false);
            $table->boolean('website_invalid')->default(false);
            $table->boolean('hours_invalid')->default(false);
            
            // Campo de notas adicionales opcional
            $table->text('notes')->nullable();
            
            // Usuario administrador que realizó el rechazo
            $table->foreignId('rejected_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_rejection_reasons');
    }
};
