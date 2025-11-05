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
            $table->decimal('delivery_fee', 8, 2)->default(0)->after('discount');
            $table->string('delivery_address')->nullable()->after('notes');
            $table->string('delivery_phone')->nullable()->after('delivery_address');
            $table->text('delivery_notes')->nullable()->after('delivery_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['delivery_fee', 'delivery_address', 'delivery_phone', 'delivery_notes']);
        });
    }
};
