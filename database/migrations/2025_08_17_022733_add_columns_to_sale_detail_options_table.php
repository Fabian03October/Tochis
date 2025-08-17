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
        Schema::table('sale_detail_options', function (Blueprint $table) {
            $table->foreignId('sale_detail_id')->constrained('sale_details')->onDelete('cascade');
            $table->foreignId('product_option_id')->nullable()->constrained('product_customization_options')->onDelete('set null');
            $table->string('type'); // 'observation' o 'specialty'
            $table->string('name');
            $table->decimal('price', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_detail_options', function (Blueprint $table) {
            $table->dropForeign(['sale_detail_id']);
            $table->dropForeign(['product_option_id']);
            $table->dropColumn(['sale_detail_id', 'product_option_id', 'type', 'name', 'price']);
        });
    }
};
