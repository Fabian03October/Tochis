<?php

require __DIR__ . '/vendor/autoload.php';

// Cargar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Inicializar el kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Zona horaria configurada: " . config('app.timezone') . PHP_EOL;
echo "Hora actual del sistema: " . now()->format('Y-m-d H:i:s T') . PHP_EOL;
echo "Hora en Santo Domingo: " . now()->setTimezone('America/Santo_Domingo')->format('Y-m-d H:i:s T') . PHP_EOL;
echo "Timestamp actual: " . time() . PHP_EOL;
echo "Fecha PHP nativa: " . date('Y-m-d H:i:s T') . PHP_EOL;
