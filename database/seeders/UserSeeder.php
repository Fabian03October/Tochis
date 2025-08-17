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
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Crear usuario cajero
        User::create([
            'name' => 'Cajero Principal',
            'email' => 'cajero@pos.com',
            'password' => Hash::make('cajero123'),
            'role' => 'cashier',
            'is_active' => true,
        ]);

        // Crear otro cajero
        User::create([
            'name' => 'María González',
            'email' => 'maria@pos.com',
            'password' => Hash::make('maria123'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }
}
