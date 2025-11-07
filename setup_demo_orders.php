<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\Product;

echo "=== Creando órdenes de demostración ===\n";

$user = User::first();
$products = Product::take(5)->get();

if (!$user || $products->count() == 0) {
    echo "No hay usuarios o productos disponibles\n";
    exit;
}

// Crear orden en preparación (cocina)
$sale1 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 120.00,
    'tax' => 19.20,
    'total' => 139.20,
    'paid_amount' => 140.00,
    'change_amount' => 0.80,
    'payment_method' => 'cash',
    'notes' => 'Orden demo - en preparación'
]);

SaleDetail::create([
    'sale_id' => $sale1->id,
    'product_id' => $products[0]->id,
    'product_name' => $products[0]->name,
    'product_price' => $products[0]->price,
    'quantity' => 2,
    'subtotal' => $products[0]->price * 2,
]);

// Crear orden lista
$sale2 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 85.00,
    'tax' => 13.60,
    'total' => 98.60,
    'paid_amount' => 100.00,
    'change_amount' => 1.40,
    'payment_method' => 'card',
    'notes' => 'Orden demo - lista'
]);

SaleDetail::create([
    'sale_id' => $sale2->id,
    'product_id' => $products[1]->id,
    'product_name' => $products[1]->name,
    'product_price' => $products[1]->price,
    'quantity' => 1,
    'subtotal' => $products[1]->price,
]);

// Marcar como lista
$sale2->markReady();

// Crear orden recibida
$sale3 = Sale::create([
    'user_id' => $user->id,
    'subtotal' => 95.00,
    'tax' => 15.20,
    'total' => 110.20,
    'paid_amount' => 110.20,
    'change_amount' => 0.00,
    'payment_method' => 'transfer',
    'notes' => 'Orden demo - recibida'
]);

SaleDetail::create([
    'sale_id' => $sale3->id,
    'product_id' => $products[2]->id,
    'product_name' => $products[2]->name,
    'product_price' => $products[2]->price,
    'quantity' => 3,
    'subtotal' => $products[2]->price * 3,
]);

// Marcar como lista y luego recibida
$sale3->markReady();
$sale3->markReceived();

echo "✅ Creadas 3 órdenes de demostración:\n";
echo "   - Orden #{$sale1->order_number}: En preparación\n";
echo "   - Orden #{$sale2->order_number}: Lista\n";
echo "   - Orden #{$sale3->order_number}: Recibida\n";
echo "\n¡Ya puedes ver el nuevo diseño en acción!\n";