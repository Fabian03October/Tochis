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
            // Solo agregar los campos que realmente faltan para el control de cocina
            $table->enum('kitchen_status', ['pending', 'in_kitchen', 'ready', 'delivered'])->default('pending')->after('delivery_status')->comment('Estado en cocina');
            $table->timestamp('kitchen_started_at')->nullable()->after('kitchen_status')->comment('Hora en que entró a cocina');
            $table->timestamp('kitchen_ready_at')->nullable()->after('kitchen_started_at')->comment('Hora en que estuvo lista');
            $table->integer('preparation_minutes')->nullable()->after('kitchen_ready_at')->comment('Tiempo total de preparación en minutos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'kitchen_status',
                'kitchen_started_at',
                'kitchen_ready_at',
                'preparation_minutes'
            ]);
        });
    }
};
