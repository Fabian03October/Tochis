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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la promoción
            $table->text('description')->nullable(); // Descripción
            $table->enum('type', ['percentage', 'fixed_amount']); // Tipo de descuento
            $table->decimal('discount_value', 8, 2); // Valor del descuento
            $table->enum('apply_to', ['all', 'category', 'product']); // A qué se aplica
            $table->json('applicable_items')->nullable(); // IDs de categorías o Platillos
            $table->decimal('minimum_amount', 8, 2)->default(0); // Monto mínimo para aplicar
            $table->integer('max_uses')->nullable(); // Máximo número de usos
            $table->integer('uses_count')->default(0); // Contador de usos
            $table->datetime('start_date'); // Fecha y hora de inicio
            $table->datetime('end_date'); // Fecha y hora de fin
            $table->boolean('is_active')->default(true); // Si está activa
            $table->foreignId('created_by')->constrained('users'); // Quién la creó
            $table->timestamps();
            
            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
