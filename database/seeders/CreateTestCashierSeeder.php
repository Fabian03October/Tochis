<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestCashierSeeder extends Seeder
{
    public function run()
    {
        // Verificar si ya existe
        $existingUser = User::where('email', 'cajero@test.com')->first();
        
        if (!$existingUser) {
            User::create([
                'name' => 'Cajero Prueba',
                'email' => 'cajero@test.com',
                'password' => Hash::make('123456'),
                'role' => 'cashier',
            ]);
            
            echo "✅ Usuario cajero creado: cajero@test.com / 123456\n";
        } else {
            echo "ℹ️ Usuario cajero ya existe: cajero@test.com\n";
        }
    }
}
