<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 PROBANDO DETECCIÓN DE COMBOS\n";
echo "================================\n\n";

// Simular datos del carrito que vemos en la imagen
$cartData = [
    [
        'id' => 1,
        'quantity' => 1,
        'name' => 'Hamburguesa Clásica',
        'price' => 85.00
    ],
    [
        'id' => 2,
        'quantity' => 1,
        'name' => 'Hamburguesa con Queso',
        'price' => 95.00
    ]
];

echo "📦 Simulando carrito:\n";
foreach ($cartData as $item) {
    echo "  - {$item['name']} (ID: {$item['id']}) - \${$item['price']}\n";
}
echo "\n";

try {
    // Crear una instancia del controlador
    $controller = new \App\Http\Controllers\Cashier\SaleController();
    
    // Crear un request simulado
    $request = new \Illuminate\Http\Request();
    $request->replace(['cart_products' => $cartData]);
    
    // Llamar al método getSuggestedCombos usando reflection
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getSuggestedCombos');
    $method->setAccessible(true);
    
    $response = $method->invoke($controller, $request);
    $result = json_decode($response->getContent(), true);
    
    echo "📊 RESULTADO DE LA DETECCIÓN:\n";
    echo "------------------------------\n";
    echo "Tiene sugerencias: " . ($result['has_suggestions'] ? 'SÍ' : 'NO') . "\n";
    echo "Número de sugerencias: " . count($result['suggestions'] ?? []) . "\n\n";
    
    if (!empty($result['suggestions'])) {
        foreach ($result['suggestions'] as $i => $suggestion) {
            $combo = $suggestion['combo'];
            $matchLevel = $suggestion['match_level'];
            
            echo "🎯 COMBO " . ($i + 1) . ":\n";
            echo "Nombre: {$combo['name']}\n";
            echo "Precio: \${$combo['price']}\n";
            echo "Coincidencia: {$matchLevel['percentage']}%\n";
            echo "Platillos coincidentes: {$matchLevel['matched_count']}/{$matchLevel['total_count']}\n";
            
            if (!empty($suggestion['missing_products'])) {
                echo "Platillos faltantes:\n";
                foreach ($suggestion['missing_products'] as $missing) {
                    echo "  - {$missing['name']} - \${$missing['price']}\n";
                }
            }
            echo "\n";
        }
    } else {
        echo "❌ No se encontraron sugerencias\n";
        
        if (isset($result['error'])) {
            echo "Error: {$result['error']}\n";
            if (isset($result['message'])) {
                echo "Mensaje: {$result['message']}\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR EN LA PRUEBA:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
