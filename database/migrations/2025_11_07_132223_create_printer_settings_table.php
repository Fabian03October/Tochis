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
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre descriptivo de la impresora');
            $table->string('model')->comment('Modelo de impresora (TM-T20, TSP100, etc)');
            $table->enum('connection_type', ['usb', 'network', 'bluetooth', 'serial'])->default('usb');
            $table->string('connection_string')->nullable()->comment('Puerto USB, IP, etc');
            $table->integer('port')->nullable()->comment('Puerto de red si aplica');
            $table->enum('paper_width', ['58mm', '80mm'])->default('80mm');
            $table->boolean('auto_cut')->default(true)->comment('Corte automático de papel');
            $table->boolean('cash_drawer')->default(false)->comment('Conectar cajón de dinero');
            $table->integer('characters_per_line')->default(48)->comment('Caracteres por línea');
            $table->json('print_settings')->nullable()->comment('Configuraciones adicionales JSON');
            $table->boolean('is_active')->default(false)->comment('Impresora activa por defecto');
            $table->boolean('is_enabled')->default(true)->comment('Impresora habilitada');
            $table->timestamp('last_test')->nullable()->comment('Última prueba de conexión');
            $table->enum('status', ['connected', 'disconnected', 'error', 'unknown'])->default('unknown');
            $table->text('last_error')->nullable()->comment('Último error reportado');
            $table->timestamps();
            
            // Índices
            $table->index(['is_active', 'is_enabled']);
            $table->index('connection_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_settings');
    }
};
