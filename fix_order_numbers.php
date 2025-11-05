<?php

require 'vendor/autoload.php';

// Configurar Laravel sin arrancar toda la aplicación
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Cargar configuración
$app->loadEnvironmentFrom('.env');
$app->bootstrapWith([
    \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
    \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
]);

use Illuminate\Support\Facades\DB;

try {
    echo "Actualizando registros de sales sin order_number...\n";
    
    $updated = DB::statement("
        UPDATE sales 
        SET order_number = CONCAT('ORD-', DATE_FORMAT(created_at, '%Y%m%d'), '-', LPAD(id, 4, '0'))
        WHERE order_number IS NULL OR order_number = ''
    ");
    
    echo "Actualización completada.\n";
    
    // Verificar registros sin order_number
    $countNull = DB::table('sales')->whereNull('order_number')->orWhere('order_number', '')->count();
    echo "Registros sin order_number después de la actualización: $countNull\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
