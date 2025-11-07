<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\Product;

echo "=== Creando órdenes de demostración para el nuevo diseño ===\n";

$user = User::first();
$products = Product::all();

if (!$user || $products->count() == 0) {
    echo "No hay usuarios o productos disponibles\n";
    exit;
}

// Crear orden 1: Hamburguesa + Bebida
$sale1 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 150.00,
    'tax' => 24.00,
    'total' => 174.00,
    'paid_amount' => 200.00,
    'change_amount' => 26.00,
    'payment_method' => 'cash',
    'notes' => 'Orden con comida y bebida'
]);

// Agregar hamburguesa (categoría 1)
$hamburguesa = $products->where('category_id', 1)->first();
if ($hamburguesa) {
    SaleDetail::create([
        'sale_id' => $sale1->id,
        'product_id' => $hamburguesa->id,
        'product_name' => $hamburguesa->name,
        'product_price' => $hamburguesa->price,
        'quantity' => 2,
        'subtotal' => $hamburguesa->price * 2,
    ]);
}

// Agregar bebida (categoría 5)
$bebida = $products->where('category_id', 5)->first();
if ($bebida) {
    SaleDetail::create([
        'sale_id' => $sale1->id,
        'product_id' => $bebida->id,
        'product_name' => $bebida->name,
        'product_price' => $bebida->price,
        'quantity' => 1,
        'subtotal' => $bebida->price,
    ]);
}

// Crear orden 2: Solo Pizza
$sale2 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 180.00,
    'tax' => 28.80,
    'total' => 208.80,
    'paid_amount' => 210.00,
    'change_amount' => 1.20,
    'payment_method' => 'card',
    'notes' => 'Orden solo comida'
]);

// Agregar pizza (categoría 2)
$pizza = $products->where('category_id', 2)->first();
if ($pizza) {
    SaleDetail::create([
        'sale_id' => $sale2->id,
        'product_id' => $pizza->id,
        'product_name' => $pizza->name,
        'product_price' => $pizza->price,
        'quantity' => 1,
        'subtotal' => $pizza->price,
    ]);
}

// Crear orden 3: Solo Bebidas
$sale3 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 60.00,
    'tax' => 9.60,
    'total' => 69.60,
    'paid_amount' => 70.00,
    'change_amount' => 0.40,
    'payment_method' => 'transfer',
    'notes' => 'Orden solo bebidas'
]);

// Agregar 2 bebidas diferentes
$bebidas = $products->where('category_id', 5)->take(2);
foreach ($bebidas as $bebida) {
    SaleDetail::create([
        'sale_id' => $sale3->id,
        'product_id' => $bebida->id,
        'product_name' => $bebida->name,
        'product_price' => $bebida->price,
        'quantity' => 1,
        'subtotal' => $bebida->price,
    ]);
}

echo "✅ Creadas 3 órdenes de demostración:\n";
echo "   - Orden #{$sale1->order_number}: Hamburguesa + Bebida\n";
echo "   - Orden #{$sale2->order_number}: Pizza\n";
echo "   - Orden #{$sale3->order_number}: Solo Bebidas\n";
echo "\n¡Ahora puedes probar el nuevo diseño con cards clickeables!\n";