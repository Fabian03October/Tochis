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
            $table->string('card_payment_reference')->nullable()->after('payment_method');
            $table->integer('card_installments')->nullable()->after('card_payment_reference');
            $table->json('card_payment_details')->nullable()->after('card_installments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['card_payment_reference', 'card_installments', 'card_payment_details']);
        });
    }
};
