<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Payment;
use MercadoPago\Item;

class MercadoPagoSetting extends Model
{
    use HasFactory;

    protected $table = 'mercadopago_settings';

    protected $fillable = [
        'name',
        'environment',
        'public_key',
        'access_token',
        'client_id',
        'client_secret',
        'point_device_id',
        'point_pos_id',
        'point_enabled',
        'qr_enabled',
        'qr_store_id',
        'qr_pos_id',
        'webhook_url',
        'webhook_secret',
        'webhook_enabled',
        'installments_enabled',
        'max_installments',
        'minimum_amount',
        'maximum_amount',
        'refunds_enabled',
        'refund_days_limit',
        'is_active',
        'is_enabled',
        'last_test',
        'status',
        'last_error',
        'additional_settings'
    ];

    protected function casts(): array
    {
        return [
            'point_enabled' => 'boolean',
            'qr_enabled' => 'boolean',
            'webhook_enabled' => 'boolean',
            'installments_enabled' => 'boolean',
            'refunds_enabled' => 'boolean',
            'is_active' => 'boolean',
            'is_enabled' => 'boolean',
            'last_test' => 'datetime',
            'minimum_amount' => 'decimal:2',
            'maximum_amount' => 'decimal:2',
            'additional_settings' => 'array',
        ];
    }

    /**
     * Ambientes disponibles
     */
    public static function getEnvironments()
    {
        return [
            'sandbox' => 'Sandbox (Pruebas)',
            'production' => 'Producción'
        ];
    }

    /**
     * Métodos de pago soportados
     */
    public static function getPaymentMethods()
    {
        return [
            'qr' => [
                'name' => 'Código QR',
                'description' => 'El cliente escanea un código QR para pagar',
                'icon' => 'fas fa-qrcode'
            ],
            'point' => [
                'name' => 'Terminal Point',
                'description' => 'Terminal física MercadoPago Point',
                'icon' => 'fas fa-credit-card'
            ],
            'link' => [
                'name' => 'Link de Pago',
                'description' => 'Enlace de pago compartido',
                'icon' => 'fas fa-link'
            ],
            'api' => [
                'name' => 'API Directa',
                'description' => 'Integración directa con tarjetas',
                'icon' => 'fas fa-code'
            ]
        ];
    }

    /**
     * Obtener la configuración activa
     */
    public static function getActive()
    {
        return static::where('is_active', true)
                    ->where('is_enabled', true)
                    ->first();
    }

    /**
     * Activar esta configuración (desactivar las demás)
     */
    public function activate()
    {
        // Desactivar todas las configuraciones
        static::where('is_active', true)->update(['is_active' => false]);
        
        // Activar esta configuración
        $this->update(['is_active' => true]);
        
        return $this;
    }

    /**
     * Inicializar SDK de MercadoPago
     */
    public function initializeSDK()
    {
        if (!$this->access_token) {
            throw new \Exception('Access Token no configurado');
        }

        SDK::setAccessToken($this->access_token);
        
        if ($this->environment === 'sandbox') {
            SDK::setIntegratorId('dev_24c65fb163bf11ea96500242ac130004');
        }

        return true;
    }

    /**
     * Probar conexión con MercadoPago
     */
    public function testConnection()
    {
        try {
            $this->initializeSDK();
            
            // Realizar una consulta simple para verificar las credenciales
            $payment = new Payment();
            $payment->save(); // Esto debería fallar pero validar las credenciales
            
        } catch (\Exception $e) {
            // Si el error es por falta de datos requeridos, las credenciales son válidas
            if (strpos($e->getMessage(), 'transaction_amount') !== false ||
                strpos($e->getMessage(), 'token') !== false ||
                strpos($e->getMessage(), 'payment_method_id') !== false) {
                
                $this->update([
                    'status' => 'connected',
                    'last_test' => now(),
                    'last_error' => null
                ]);
                
                return true;
            }
            
            // Error real de credenciales
            $this->update([
                'status' => 'error',
                'last_test' => now(),
                'last_error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Crear preferencia de pago
     */
    public function createPaymentPreference($saleData)
    {
        $this->initializeSDK();

        $preference = new Preference();
        
        // Configurar items
        $items = [];
        foreach ($saleData['items'] as $itemData) {
            $item = new Item();
            $item->title = $itemData['title'];
            $item->quantity = $itemData['quantity'];
            $item->unit_price = (float)$itemData['unit_price'];
            $items[] = $item;
        }
        
        $preference->items = $items;
        
        // Configurar URLs de retorno
        $preference->back_urls = [
            'success' => $saleData['success_url'] ?? route('cashier.sale.index'),
            'failure' => $saleData['failure_url'] ?? route('cashier.sale.index'),
            'pending' => $saleData['pending_url'] ?? route('cashier.sale.index')
        ];
        
        $preference->auto_return = 'approved';
        
        // Configurar referencia externa
        $preference->external_reference = $saleData['external_reference'] ?? 'SALE-' . time();
        
        // Configurar cuotas si están habilitadas
        if ($this->installments_enabled) {
            $preference->payment_methods = [
                'installments' => $this->max_installments
            ];
        }

        // Configurar notificaciones
        if ($this->webhook_enabled && $this->webhook_url) {
            $preference->notification_url = $this->webhook_url;
        }

        $preference->save();
        
        return $preference;
    }

    /**
     * Obtener información de un pago
     */
    public function getPayment($paymentId)
    {
        $this->initializeSDK();
        
        $payment = Payment::find_by_id($paymentId);
        
        return $payment;
    }

    /**
     * Procesar reembolso
     */
    public function refundPayment($paymentId, $amount = null)
    {
        if (!$this->refunds_enabled) {
            throw new \Exception('Reembolsos no habilitados en esta configuración');
        }

        $this->initializeSDK();
        
        $payment = Payment::find_by_id($paymentId);
        
        if (!$payment) {
            throw new \Exception('Pago no encontrado');
        }

        $refund = $payment->refund($amount);
        
        return $refund;
    }

    /**
     * Generar código QR para pago
     */
    public function generateQRCode($amount, $description, $externalReference = null)
    {
        if (!$this->qr_enabled) {
            throw new \Exception('Pagos por QR no habilitados');
        }

        // Aquí implementarías la lógica específica para QR según el tipo
        // Por ahora retornamos un placeholder
        
        return [
            'qr_data' => 'https://mercadopago.com/instore/qr/' . $externalReference,
            'qr_code' => base64_encode('QR_CODE_DATA'), // Placeholder
            'amount' => $amount,
            'description' => $description
        ];
    }

    /**
     * Validar webhook de MercadoPago
     */
    public function validateWebhook($headers, $body)
    {
        if (!$this->webhook_enabled || !$this->webhook_secret) {
            return false;
        }

        // Implementar validación de webhook según la documentación de MP
        $signature = $headers['x-signature'] ?? '';
        $requestId = $headers['x-request-id'] ?? '';
        
        // Validar firma
        $expectedSignature = hash_hmac('sha256', $body, $this->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Obtener métodos de pago disponibles
     */
    public function getAvailablePaymentMethods()
    {
        $this->initializeSDK();
        
        try {
            // Consultar métodos de pago disponibles
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.mercadopago.com/v1/payment_methods",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $this->access_token
                ]
            ]);
            
            $response = curl_exec($curl);
            curl_close($curl);
            
            return json_decode($response, true);
            
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener métodos de pago: ' . $e->getMessage());
        }
    }

    /**
     * Obtener información de la cuenta
     */
    public function getAccountInfo()
    {
        $this->initializeSDK();
        
        try {
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.mercadopago.com/users/me",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $this->access_token
                ]
            ]);
            
            $response = curl_exec($curl);
            curl_close($curl);
            
            return json_decode($response, true);
            
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener información de cuenta: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si está en ambiente de producción
     */
    public function isProduction()
    {
        return $this->environment === 'production';
    }

    /**
     * Verificar si está configurado correctamente
     */
    public function isConfigured()
    {
        return !empty($this->access_token) && !empty($this->public_key);
    }

    /**
     * Obtener URL del ambiente
     */
    public function getApiUrl()
    {
        return $this->environment === 'production' 
            ? 'https://api.mercadopago.com' 
            : 'https://api.mercadopago.com'; // MP usa la misma URL para ambos
    }
}
