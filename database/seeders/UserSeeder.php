<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Crear usuario cajero
        User::firstOrCreate(
            ['email' => 'cajero@pos.com'],
            [
                'name' => 'Cajero Principal',
                'password' => Hash::make('cajero123'),
                'role' => 'cashier',
                'is_active' => true,
            ]
        );

        // Crear otro cajero
        User::firstOrCreate(
            ['email' => 'maria@pos.com'],
            [
                'name' => 'María González',
                'password' => Hash::make('maria123'),
                'role' => 'cashier',
                'is_active' => true,
            ]
        );
    }
}
