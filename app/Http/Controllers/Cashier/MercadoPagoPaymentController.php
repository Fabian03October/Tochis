<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\MercadoPagoSetting;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\PointDevices;
use MercadoPago\PointPaymentIntent;

class MercadoPagoPaymentController extends Controller
{
    protected $mpSettings;

    public function __construct()
    {
        // Obtener configuración activa de MercadoPago
        $this->mpSettings = MercadoPagoSetting::where('is_active', true)->first();
        
        if ($this->mpSettings) {
            // Configurar SDK de MercadoPago
            SDK::setAccessToken($this->mpSettings->access_token);
            
            if ($this->mpSettings->environment === 'sandbox') {
                SDK::setIntegratorId("dev_24c65fb163bf11ea96500242ac130004");
            }
        }
    }

    /**
     * Obtener configuración de MercadoPago para el cajero
     */
    public function getConfig()
    {
        try {
            if (!$this->mpSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay configuración de MercadoPago activa'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'config' => [
                    'environment' => $this->mpSettings->environment,
                    'public_key' => $this->mpSettings->public_key,
                    'has_point_device' => !empty($this->mpSettings->point_device_id),
                    'point_device_id' => $this->mpSettings->point_device_id,
                    'max_installments' => $this->mpSettings->max_installments,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo configuración MercadoPago: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener configuración de pagos'
            ], 500);
        }
    }

    /**
     * Listar dispositivos Point disponibles
     */
    public function getPointDevices()
    {
        try {
            if (!$this->mpSettings || empty($this->mpSettings->point_device_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay dispositivos Point configurados'
                ], 400);
            }

            $devices = new PointDevices();
            $response = $devices->all();

            return response()->json([
                'success' => true,
                'devices' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo dispositivos Point: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener dispositivos Point'
            ], 500);
        }
    }

    /**
     * Procesar pago con tarjeta usando MercadoPago Point
     */
    public function processCardPayment(Request $request)
    {
        try {
            // Validar datos de entrada
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'sale_id' => 'required|integer|exists:sales,id',
                'installments' => 'nullable|integer|min:1|max:24'
            ]);

            if (!$this->mpSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay configuración de MercadoPago activa'
                ], 400);
            }

            // Obtener la venta
            $sale = Sale::find($request->sale_id);
            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'Venta no encontrada'
                ], 404);
            }

            $amount = floatval($request->amount);
            $installments = intval($request->installments ?? 1);

            // Crear intención de pago en Point
            $paymentIntent = new PointPaymentIntent();
            $paymentIntent->amount = $amount;
            $paymentIntent->description = "Venta #{$sale->sale_number} - TOCHIS POS";
            $paymentIntent->device_id = $this->mpSettings->point_device_id;
            
            // Configurar cuotas si se especifican
            if ($installments > 1) {
                $paymentIntent->payment_method_types = ["credit_card"];
                $paymentIntent->installments = $installments;
            }

            // Metadata adicional
            $paymentIntent->additional_info = [
                'external_reference' => "SALE_{$sale->id}",
                'sale_id' => $sale->id,
                'cashier_id' => auth()->id(),
                'pos_system' => 'TOCHIS_POS'
            ];

            Log::info('Creando intención de pago Point', [
                'sale_id' => $sale->id,
                'amount' => $amount,
                'device_id' => $this->mpSettings->point_device_id
            ]);

            // Enviar intención de pago al dispositivo
            $response = $paymentIntent->save();

            if ($response) {
                // Guardar intención de pago en la base de datos
                DB::table('mercadopago_payment_intents')->insert([
                    'sale_id' => $sale->id,
                    'intent_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'device_id' => $this->mpSettings->point_device_id,
                    'installments' => $installments,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pago enviado al dispositivo Point. Espere confirmación...',
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'installments' => $installments,
                    'device_id' => $this->mpSettings->point_device_id
                ]);
            } else {
                throw new \Exception('Error al crear intención de pago');
            }

        } catch (\Exception $e) {
            Log::error('Error procesando pago con tarjeta: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado de pago Point
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $intentId = $request->payment_intent_id;

            // Buscar intención de pago en BD
            $intentRecord = DB::table('mercadopago_payment_intents')
                              ->where('intent_id', $intentId)
                              ->first();

            if (!$intentRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intención de pago no encontrada'
                ], 404);
            }

            // Consultar estado en MercadoPago
            $paymentIntent = new PointPaymentIntent();
            $paymentIntent->id = $intentId;
            $paymentIntent->read();

            Log::info('Estado de pago Point consultado', [
                'intent_id' => $intentId,
                'status' => $paymentIntent->status ?? 'unknown'
            ]);

            // Actualizar estado en BD
            DB::table('mercadopago_payment_intents')
              ->where('intent_id', $intentId)
              ->update([
                  'status' => $paymentIntent->status ?? 'unknown',
                  'updated_at' => now()
              ]);

            return response()->json([
                'success' => true,
                'status' => $paymentIntent->status ?? 'unknown',
                'payment_intent' => $paymentIntent
            ]);

        } catch (\Exception $e) {
            Log::error('Error verificando estado de pago: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar estado del pago'
            ], 500);
        }
    }

    /**
     * Cancelar intención de pago Point
     */
    public function cancelPayment(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $intentId = $request->payment_intent_id;

            // Buscar intención de pago
            $intentRecord = DB::table('mercadopago_payment_intents')
                              ->where('intent_id', $intentId)
                              ->first();

            if (!$intentRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intención de pago no encontrada'
                ], 404);
            }

            // Cancelar en MercadoPago
            $paymentIntent = new PointPaymentIntent();
            $paymentIntent->id = $intentId;
            $response = $paymentIntent->cancel();

            // Actualizar estado en BD
            DB::table('mercadopago_payment_intents')
              ->where('intent_id', $intentId)
              ->update([
                  'status' => 'cancelled',
                  'updated_at' => now()
              ]);

            Log::info('Pago Point cancelado', ['intent_id' => $intentId]);

            return response()->json([
                'success' => true,
                'message' => 'Pago cancelado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelando pago: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar pago'
            ], 500);
        }
    }
}