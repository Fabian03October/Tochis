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
            // Agregar campo para número de orden consecutivo diario
            $table->integer('order_number')->after('id')->nullable()->comment('Número de orden consecutivo por día');
            $table->date('order_date')->after('order_number')->nullable()->comment('Fecha de la orden para reiniciar numeración');
            
            // Índice para búsqueda rápida por fecha y número
            $table->index(['order_date', 'order_number'], 'idx_sales_order_date_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_order_date_number');
            $table->dropColumn(['order_number', 'order_date']);
        });
    }
};
