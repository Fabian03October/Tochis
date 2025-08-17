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
        // Primero, verificar si la tabla tiene las columnas correctas
        if (Schema::hasTable('category_customization_option')) {
            // Si no tiene las columnas correctas, eliminar y recrear
            if (!Schema::hasColumn('category_customization_option', 'category_id')) {
                Schema::dropIfExists('category_customization_option');
                
                Schema::create('category_customization_option', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id');
                    $table->unsignedBigInteger('product_customization_option_id');
                    $table->timestamps();
                    
                    // Claves foráneas con nombres cortos
                    $table->foreign('category_id', 'cat_cust_opt_cat_fk')->references('id')->on('categories')->onDelete('cascade');
                    $table->foreign('product_customization_option_id', 'cat_cust_opt_pco_fk')->references('id')->on('product_customization_options')->onDelete('cascade');
                    
                    // Evitar duplicados
                    $table->unique(['category_id', 'product_customization_option_id'], 'category_option_unique');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada, mantenemos la tabla como está
    }
};
