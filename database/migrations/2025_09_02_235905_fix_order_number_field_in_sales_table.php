<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si existe el índice antes de intentar eliminarlo
        $indexes = DB::select("SHOW INDEX FROM sales WHERE Key_name = 'sales_order_number_index'");
        
        if (!empty($indexes)) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropIndex(['order_number']);
            });
        }
        
        // Actualizar registros existentes que no tengan order_number
        DB::statement("
            UPDATE sales 
            SET order_number = CONCAT('ORD-', DATE_FORMAT(created_at, '%Y%m%d'), '-', LPAD(id, 4, '0'))
            WHERE order_number IS NULL OR order_number = ''
        ");
        
        // Verificar si ya existe un índice único antes de crearlo
        $uniqueIndexes = DB::select("SHOW INDEX FROM sales WHERE Key_name = 'sales_order_number_unique'");
        
        if (empty($uniqueIndexes)) {
            Schema::table('sales', function (Blueprint $table) {
                $table->unique('order_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // No hacer nada en el rollback para evitar problemas
        });
    }
};
