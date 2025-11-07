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
        Schema::create('mercadopago_payment_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('intent_id')->unique(); // ID de la intención de pago en MercadoPago
            $table->decimal('amount', 10, 2); // Monto del pago
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->string('device_id')->nullable(); // ID del dispositivo Point
            $table->integer('installments')->default(1); // Número de cuotas
            $table->string('payment_id')->nullable(); // ID del pago una vez aprobado
            $table->string('payment_method')->nullable(); // Método de pago usado
            $table->json('payment_data')->nullable(); // Datos adicionales del pago
            $table->timestamp('payment_date')->nullable(); // Fecha del pago aprobado
            $table->text('error_message')->nullable(); // Mensaje de error si falla
            $table->timestamps();
            
            $table->index(['sale_id', 'status']);
            $table->index('intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mercadopago_payment_intents');
    }
};
