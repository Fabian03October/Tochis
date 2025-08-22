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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2); // Precio sum de productos individuales
            $table->decimal('discount_amount', 10, 2); // Descuento del combo
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->integer('min_items')->default(2); // Mínimo de productos para sugerir
            $table->boolean('auto_suggest')->default(true); // Si se sugiere automáticamente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
