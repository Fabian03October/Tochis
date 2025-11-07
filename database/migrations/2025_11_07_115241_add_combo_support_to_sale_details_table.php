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
        Schema::table('sale_details', function (Blueprint $table) {
            $table->foreignId('combo_id')->nullable()->after('product_id')->constrained('combos')->onDelete('set null');
            $table->string('combo_name')->nullable()->after('combo_id'); // Para histórico
            $table->decimal('combo_price', 10, 2)->nullable()->after('combo_name'); // Para histórico
            $table->enum('item_type', ['product', 'combo'])->default('product')->after('combo_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropForeign(['combo_id']);
            $table->dropColumn(['combo_id', 'combo_name', 'combo_price', 'item_type']);
        });
    }
};
