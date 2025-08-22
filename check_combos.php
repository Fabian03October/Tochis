<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$combos = App\Models\Combo::with('products')->get();

echo "=== VERIFICACIÓN DE COMBOS ===" . PHP_EOL;
foreach($combos as $combo) {
    echo "Combo: {$combo->name} - Productos: {$combo->products->count()}" . PHP_EOL;
}

echo PHP_EOL . "Total de combos: " . $combos->count() . PHP_EOL;
