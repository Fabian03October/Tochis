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
        Schema::create('mercadopago_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre descriptivo de la configuración');
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');
            
            // Credenciales de API
            $table->string('public_key')->nullable()->comment('Public Key de MercadoPago');
            $table->string('access_token')->nullable()->comment('Access Token de MercadoPago');
            $table->string('client_id')->nullable()->comment('Client ID de la aplicación');
            $table->string('client_secret')->nullable()->comment('Client Secret de la aplicación');
            
            // Configuración de Point (terminales físicas)
            $table->string('point_device_id')->nullable()->comment('ID del dispositivo Point');
            $table->string('point_pos_id')->nullable()->comment('ID del POS Point');
            $table->boolean('point_enabled')->default(false)->comment('Point habilitado');
            
            // Configuración de QR
            $table->boolean('qr_enabled')->default(true)->comment('Pagos por QR habilitados');
            $table->string('qr_store_id')->nullable()->comment('ID de tienda para QR');
            $table->string('qr_pos_id')->nullable()->comment('ID de POS para QR');
            
            // Configuración de Webhooks
            $table->string('webhook_url')->nullable()->comment('URL para recibir notificaciones');
            $table->string('webhook_secret')->nullable()->comment('Secreto para validar webhooks');
            $table->boolean('webhook_enabled')->default(false);
            
            // Configuración de pagos
            $table->boolean('installments_enabled')->default(true)->comment('Cuotas habilitadas');
            $table->integer('max_installments')->default(12)->comment('Máximo de cuotas');
            $table->decimal('minimum_amount', 10, 2)->default(1.00)->comment('Monto mínimo');
            $table->decimal('maximum_amount', 10, 2)->nullable()->comment('Monto máximo');
            
            // Configuración de devoluciones
            $table->boolean('refunds_enabled')->default(true)->comment('Devoluciones habilitadas');
            $table->integer('refund_days_limit')->default(90)->comment('Días límite para devoluciones');
            
            // Estados y control
            $table->boolean('is_active')->default(false)->comment('Configuración activa');
            $table->boolean('is_enabled')->default(true)->comment('Configuración habilitada');
            $table->timestamp('last_test')->nullable()->comment('Última prueba de conexión');
            $table->enum('status', ['connected', 'disconnected', 'error', 'unknown'])->default('unknown');
            $table->text('last_error')->nullable()->comment('Último error reportado');
            
            // Configuraciones adicionales JSON
            $table->json('additional_settings')->nullable()->comment('Configuraciones extra');
            
            $table->timestamps();
            
            // Índices
            $table->index(['is_active', 'is_enabled']);
            $table->index('environment');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mercadopago_settings');
    }
};
