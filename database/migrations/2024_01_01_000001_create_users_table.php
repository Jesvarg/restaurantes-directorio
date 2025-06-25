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
        // Tabla de usuarios para autenticación y gestión de restaurantes
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // name: nombre completo del usuario, obligatorio
            $table->string('name');
            // email: correo único para login, obligatorio
            $table->string('email')->unique();
            // email_verified_at: timestamp de verificación de email
            $table->timestamp('email_verified_at')->nullable();
            // password: contraseña hasheada, obligatorio
            $table->string('password');
            // remember_token: token para "recordarme"
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};