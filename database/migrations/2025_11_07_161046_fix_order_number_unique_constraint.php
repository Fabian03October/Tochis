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
            // Eliminar el índice único incorrecto en order_number
            $table->dropUnique('sales_order_number_unique');
            
            // Crear índice único correcto en la combinación order_date + order_number
            $table->unique(['order_date', 'order_number'], 'sales_order_date_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Eliminar el índice único correcto
            $table->dropUnique('sales_order_date_number_unique');
            
            // Restaurar el índice único incorrecto (para poder hacer rollback)
            $table->unique('order_number', 'sales_order_number_unique');
        });
    }
};
