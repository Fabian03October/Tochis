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
            // Agregar campo para identificar si va a cocina o barra
            $table->enum('station_type', ['kitchen', 'bar'])->default('kitchen')->after('kitchen_status')->comment('Tipo de estación: cocina o barra');
            
            // Actualizar estados para incluir "received" (recibido por cajero)
            $table->dropColumn('kitchen_status');
        });
        
        // Recrear la columna con los nuevos estados
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('kitchen_status', ['pending', 'in_kitchen', 'in_bar', 'ready', 'received', 'delivered'])
                  ->default('pending')
                  ->after('delivery_status')
                  ->comment('Estado en cocina/barra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Eliminar station_type
            $table->dropColumn('station_type');
            
            // Restaurar kitchen_status original
            $table->dropColumn('kitchen_status');
        });
        
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('kitchen_status', ['pending', 'in_kitchen', 'ready', 'delivered'])
                  ->default('pending')
                  ->after('delivery_status')
                  ->comment('Estado en cocina');
        });
    }
};
