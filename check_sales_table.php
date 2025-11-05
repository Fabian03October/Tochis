<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\Schema;

echo "Columnas de la tabla 'sales':\n";
$columns = Schema::getColumnListing('sales');
foreach($columns as $column) {
    echo "- $column\n";
}

// Verificar si order_number permite NULL
echo "\nDetalles de la columna order_number:\n";
$tableDetails = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails('sales');
$orderNumberColumn = $tableDetails->getColumn('order_number');

echo "- Nullable: " . ($orderNumberColumn->getNotnull() ? 'NO' : 'YES') . "\n";
echo "- Default: " . ($orderNumberColumn->getDefault() ?? 'NULL') . "\n";

// Verificar restricciones unique
echo "\nÍndices en la tabla:\n";
$indexes = $tableDetails->getIndexes();
foreach($indexes as $indexName => $index) {
    if($index->isUnique()) {
        echo "- Unique index '$indexName': " . implode(', ', $index->getColumns()) . "\n";
    }
}
