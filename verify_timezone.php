<?php

// Verificar configuración de zona horaria
echo "=== VERIFICACIÓN DE ZONA HORARIA ===\n";
echo "Zona horaria del sistema: " . date_default_timezone_get() . "\n";
echo "Fecha/Hora actual del sistema: " . date('Y-m-d H:i:s T') . "\n";

// Cargar configuración de Laravel (simulando)
$timezone = 'America/Mexico_City';
date_default_timezone_set($timezone);

echo "\n=== DESPUÉS DE CONFIGURAR ZONA HORARIA ===\n";
echo "Nueva zona horaria: " . date_default_timezone_get() . "\n";
echo "Nueva fecha/hora: " . date('Y-m-d H:i:s T') . "\n";

// Simular Carbon
if (class_exists('DateTime')) {
    $dt = new DateTime('now', new DateTimeZone($timezone));
    echo "DateTime con zona horaria: " . $dt->format('Y-m-d H:i:s T') . "\n";
}

echo "\n=== INFORMACIÓN ADICIONAL ===\n";
echo "UTC Offset: " . date('P') . "\n";
echo "Tiempo Unix: " . time() . "\n";
