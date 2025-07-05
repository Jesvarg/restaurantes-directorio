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
        User::firstOrCreate(
            ['email' => 'admin@restaurantes.com'],
            [
                'name' => 'Administrador',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create restaurant owner
        User::firstOrCreate(
            ['email' => 'owner@restaurantes.com'],
            [
                'name' => 'Propietario Demo',
                'email_verified_at' => now(),
                'password' => Hash::make('owner123'),
                'role' => 'owner',
            ]
        );

        // Create regular users
        User::firstOrCreate(
            ['email' => 'user@restaurantes.com'],
            [
                'name' => 'Usuario Demo',
                'email_verified_at' => now(),
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // Create additional test users only if they don't exist
        $existingUsersCount = User::where('email', 'like', 'test%@example.com')->count();
        if ($existingUsersCount < 10) {
            User::factory(10 - $existingUsersCount)->create();
        }

        $this->command->info('Users seeded successfully!');
    }
}