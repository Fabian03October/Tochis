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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('sale_number');
            $table->enum('payment_status', ['pending', 'paid'])->default('paid')->after('status');
            $table->enum('delivery_status', ['pending', 'delivered'])->default('delivered')->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('delivery_status');
            $table->timestamp('delivered_at')->nullable()->after('paid_at');
            $table->unsignedBigInteger('delivered_by')->nullable()->after('delivered_at');
            
            $table->foreign('delivered_by')->references('id')->on('users')->onDelete('set null');
        });

        // Generar números de orden para registros existentes
        DB::statement("
            UPDATE sales 
            SET order_number = CONCAT('ORD-', DATE_FORMAT(created_at, '%Y%m%d'), '-', LPAD(id, 4, '0'))
            WHERE order_number IS NULL
        ");

        // Actualizar timestamps para registros existentes
        DB::statement("
            UPDATE sales 
            SET paid_at = created_at, delivered_at = updated_at
            WHERE paid_at IS NULL
        ");

        // Ahora hacer el campo único
        Schema::table('sales', function (Blueprint $table) {
            $table->unique('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropColumn([
                'order_number',
                'payment_status', 
                'delivery_status',
                'paid_at',
                'delivered_at',
                'delivered_by'
            ]);
        });
    }
};
