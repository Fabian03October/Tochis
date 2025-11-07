<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MercadoPagoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MercadoPagoController extends Controller
{
    /**
     * Display a listing of MercadoPago configurations.
     */
    public function index()
    {
        $settings = MercadoPagoSetting::orderBy('is_active', 'desc')
                                          ->orderBy('created_at', 'desc')
                                          ->get();

        return view('admin.mercadopago.index', compact('settings'));
    }

    /**
     * Show the form for creating a new configuration.
     */
    public function create()
    {
        return view('admin.mercadopago.create');
    }

    /**
     * Store a newly created configuration in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'environment' => 'required|in:sandbox,production',
            'public_key' => 'required|string',
            'access_token' => 'required|string',
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'point_device_id' => 'nullable|string',
            'point_pos_id' => 'nullable|string',
            'qr_store_id' => 'nullable|string',
            'qr_pos_id' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'webhook_secret' => 'nullable|string',
            'max_installments' => 'required|integer|min:1|max:24',
            'minimum_amount' => 'required|numeric|min:0.01',
            'maximum_amount' => 'nullable|numeric|gt:minimum_amount',
            'refund_days_limit' => 'required|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();
        
        // Convertir checkboxes
        $data['point_enabled'] = $request->has('point_enabled');
        $data['qr_enabled'] = $request->has('qr_enabled');
        $data['webhook_enabled'] = $request->has('webhook_enabled');
        $data['installments_enabled'] = $request->has('installments_enabled');
        $data['refunds_enabled'] = $request->has('refunds_enabled');
        $data['is_enabled'] = $request->has('is_enabled');
        
        $data['status'] = 'unknown';

        MercadoPagoSetting::create($data);

        return redirect()->route('admin.mercadopago.index')
                        ->with('success', 'Configuración de MercadoPago creada exitosamente.');
    }

    /**
     * Display the specified configuration.
     */
    public function show(MercadoPagoSetting $mercadopago)
    {
        try {
            // Obtener información adicional de la cuenta
            $accountInfo = $mercadopago->getAccountInfo();
            $paymentMethods = $mercadopago->getAvailablePaymentMethods();
            
            return view('admin.mercadopago.show', compact('mercadopago', 'accountInfo', 'paymentMethods'));
        } catch (\Exception $e) {
            return view('admin.mercadopago.show', [
                'mercadopago' => $mercadopago,
                'accountInfo' => null,
                'paymentMethods' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified configuration.
     */
    public function edit(MercadoPagoSetting $mercadopago)
    {
        return view('admin.mercadopago.edit', compact('mercadopago'));
    }

    /**
     * Update the specified configuration in storage.
     */
    public function update(Request $request, MercadoPagoSetting $mercadopago)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'environment' => 'required|in:sandbox,production',
            'public_key' => 'required|string',
            'access_token' => 'required|string',
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'point_device_id' => 'nullable|string',
            'point_pos_id' => 'nullable|string',
            'qr_store_id' => 'nullable|string',
            'qr_pos_id' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'webhook_secret' => 'nullable|string',
            'max_installments' => 'required|integer|min:1|max:24',
            'minimum_amount' => 'required|numeric|min:0.01',
            'maximum_amount' => 'nullable|numeric|gt:minimum_amount',
            'refund_days_limit' => 'required|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();
        
        // Convertir checkboxes
        $data['point_enabled'] = $request->has('point_enabled');
        $data['qr_enabled'] = $request->has('qr_enabled');
        $data['webhook_enabled'] = $request->has('webhook_enabled');
        $data['installments_enabled'] = $request->has('installments_enabled');
        $data['refunds_enabled'] = $request->has('refunds_enabled');
        $data['is_enabled'] = $request->has('is_enabled');

        $mercadopago->update($data);

        return redirect()->route('admin.mercadopago.index')
                        ->with('success', 'Configuración de MercadoPago actualizada exitosamente.');
    }

    /**
     * Remove the specified configuration from storage.
     */
    public function destroy(MercadoPagoSetting $mercadopago)
    {
        if ($mercadopago->is_active) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar la configuración activa.');
        }

        $mercadopago->delete();

        return redirect()->route('admin.mercadopago.index')
                        ->with('success', 'Configuración de MercadoPago eliminada exitosamente.');
    }

    /**
     * Activate a MercadoPago configuration.
     */
    public function activate(MercadoPagoSetting $mercadopago)
    {
        if (!$mercadopago->is_enabled) {
            return redirect()->back()
                           ->with('error', 'No se puede activar una configuración deshabilitada.');
        }

        if (!$mercadopago->isConfigured()) {
            return redirect()->back()
                           ->with('error', 'La configuración no está completa. Verifica las credenciales.');
        }

        $mercadopago->activate();

        return redirect()->back()
                        ->with('success', 'Configuración de MercadoPago activada exitosamente.');
    }

    /**
     * Test MercadoPago connection.
     */
    public function test(MercadoPagoSetting $mercadopago)
    {
        $result = $mercadopago->testConnection();

        if ($result) {
            return redirect()->back()
                           ->with('success', 'Conexión con MercadoPago exitosa.');
        } else {
            return redirect()->back()
                           ->with('error', 'Error de conexión: ' . ($mercadopago->last_error ?? 'Desconocido'));
        }
    }

    /**
     * Generate a test QR code.
     */
    public function generateTestQR(MercadoPagoSetting $mercadopago)
    {
        try {
            $qrData = $mercadopago->generateQRCode(
                100.00,
                'Test QR - TOCHIS POS',
                'TEST-QR-' . time()
            );

            return response()->json([
                'success' => true,
                'qr_data' => $qrData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Create a test payment preference.
     */
    public function createTestPayment(MercadoPagoSetting $mercadopago)
    {
        try {
            $testSaleData = [
                'items' => [
                    [
                        'title' => 'Test Product - TOCHIS',
                        'quantity' => 1,
                        'unit_price' => 100.00
                    ]
                ],
                'external_reference' => 'TEST-PAYMENT-' . time(),
                'success_url' => route('admin.mercadopago.index'),
                'failure_url' => route('admin.mercadopago.index'),
                'pending_url' => route('admin.mercadopago.index')
            ];

            $preference = $mercadopago->createPaymentPreference($testSaleData);

            return response()->json([
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle webhook notifications from MercadoPago.
     */
    public function webhook(Request $request)
    {
        try {
            $activeMp = MercadoPagoSetting::getActive();
            
            if (!$activeMp || !$activeMp->webhook_enabled) {
                return response()->json(['status' => 'webhook_disabled'], 200);
            }

            $headers = $request->headers->all();
            $body = $request->getContent();

            if (!$activeMp->validateWebhook($headers, $body)) {
                return response()->json(['status' => 'invalid_signature'], 400);
            }

            $data = json_decode($body, true);

            // Procesar la notificación según el tipo
            switch ($data['type'] ?? '') {
                case 'payment':
                    $this->processPaymentNotification($data, $activeMp);
                    break;
                case 'plan':
                    $this->processPlanNotification($data, $activeMp);
                    break;
                case 'subscription':
                    $this->processSubscriptionNotification($data, $activeMp);
                    break;
                default:
                    \Log::info('Unknown webhook type: ' . ($data['type'] ?? 'undefined'));
            }

            return response()->json(['status' => 'ok'], 200);

        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Process payment notification from webhook.
     */
    private function processPaymentNotification($data, $mpSetting)
    {
        $paymentId = $data['data']['id'] ?? null;
        
        if (!$paymentId) {
            return;
        }

        try {
            $payment = $mpSetting->getPayment($paymentId);
            
            // Aquí procesarías el pago según tu lógica de negocio
            // Por ejemplo, actualizar el estado de una venta
            
            \Log::info('Payment processed: ' . $paymentId, [
                'status' => $payment->status,
                'external_reference' => $payment->external_reference
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error processing payment notification: ' . $e->getMessage());
        }
    }

    /**
     * Process plan notification from webhook.
     */
    private function processPlanNotification($data, $mpSetting)
    {
        // Lógica para procesar notificaciones de planes
        \Log::info('Plan notification received', $data);
    }

    /**
     * Process subscription notification from webhook.
     */
    private function processSubscriptionNotification($data, $mpSetting)
    {
        // Lógica para procesar notificaciones de suscripciones
        \Log::info('Subscription notification received', $data);
    }

    /**
     * Get configuration data for API/AJAX.
     */
    public function getConfig(MercadoPagoSetting $mercadopago)
    {
        return response()->json([
            'success' => true,
            'configuration' => $mercadopago,
            'environments' => MercadoPagoSetting::getEnvironments(),
            'payment_methods' => MercadoPagoSetting::getPaymentMethods(),
            'is_configured' => $mercadopago->isConfigured(),
            'is_production' => $mercadopago->isProduction()
        ]);
    }
}
