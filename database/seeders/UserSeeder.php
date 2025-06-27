<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@restaurantes.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create restaurant owner
        User::create([
            'name' => 'Propietario Demo',
            'email' => 'owner@restaurantes.com',
            'email_verified_at' => now(),
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        // Create regular users
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'user@restaurantes.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // Create additional test users
        User::factory(10)->create();

        echo "Users seeded successfully!\n";
    }
}