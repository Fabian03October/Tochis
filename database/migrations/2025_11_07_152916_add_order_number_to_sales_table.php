<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Agregar campos para número de orden consecutivo diario
            if (!Schema::hasColumn('sales', 'order_number')) {
                $table->integer('order_number')->after('id')->nullable()->comment('Número de orden consecutivo por día');
            }
            if (!Schema::hasColumn('sales', 'order_date')) {
                $table->date('order_date')->after('order_number')->nullable()->comment('Fecha de la orden para reiniciar numeración');
            }
        });
        
        // Crear índice por separado
        if (!Schema::hasIndex('sales', 'idx_sales_order_date_number')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->index(['order_date', 'order_number'], 'idx_sales_order_date_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Eliminar índice y columnas
            $table->dropIndex('idx_sales_order_date_number');
            $table->dropColumn(['order_date', 'order_number']);
        });
    }
};
