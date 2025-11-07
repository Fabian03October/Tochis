<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "=== Categorías disponibles ===\n";
$categories = Category::select('id', 'name')->get();
foreach ($categories as $cat) {
    echo $cat->id . ': ' . $cat->name . PHP_EOL;
}