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
        Schema::create('product_customization_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['observation', 'specialty']); // observation = quitar, specialty = agregar
            $table->decimal('price', 8, 2)->default(0.00); // precio adicional (para especialidades)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customization_options');
    }
};
