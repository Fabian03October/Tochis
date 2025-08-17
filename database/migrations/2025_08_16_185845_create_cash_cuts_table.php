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
        Schema::create('cash_cuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Cajero
            $table->decimal('initial_amount', 10, 2)->default(0); // Dinero inicial
            $table->decimal('sales_amount', 10, 2)->default(0); // Total de ventas
            $table->decimal('final_amount', 10, 2)->default(0); // Dinero final declarado
            $table->decimal('expected_amount', 10, 2)->default(0); // Dinero esperado
            $table->decimal('difference', 10, 2)->default(0); // Diferencia (sobrante/faltante)
            $table->integer('total_sales')->default(0); // Número total de ventas
            $table->text('notes')->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_cuts');
    }
};
