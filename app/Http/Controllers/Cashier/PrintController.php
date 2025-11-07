<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\ThermalPrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrintController extends Controller
{
    protected $printService;

    public function __construct(ThermalPrintService $printService)
    {
        $this->printService = $printService;
    }

    /**
     * Imprimir ticket de venta
     */
    public function printSaleTicket(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id'
            ]);

            $sale = Sale::with(['saleDetails.options', 'user'])->findOrFail($request->sale_id);

            // Verificar que el usuario tenga acceso a esta venta (opcional)
            // if ($sale->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            //     return response()->json(['success' => false, 'message' => 'No tienes acceso a esta venta'], 403);
            // }

            $this->printService->printSaleTicket($sale);

            Log::info('Ticket impreso exitosamente', [
                'sale_id' => $sale->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket impreso exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error imprimiendo ticket: ' . $e->getMessage(), [
                'sale_id' => $request->sale_id ?? null,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al imprimir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reimprimir ticket de venta
     */
    public function reprintSaleTicket(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id'
            ]);

            $sale = Sale::with(['saleDetails.options', 'user'])->findOrFail($request->sale_id);

            $this->printService->printSaleTicket($sale);

            Log::info('Ticket reimpreso', [
                'sale_id' => $sale->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket reimpreso exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error reimprimiendo ticket: ' . $e->getMessage(), [
                'sale_id' => $request->sale_id ?? null,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al reimprimir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Probar impresora
     */
    public function testPrinter()
    {
        try {
            $this->printService->testPrinter();

            Log::info('Prueba de impresora exitosa', [
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prueba de impresora exitosa'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en prueba de impresora: ' . $e->getMessage(), [
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en prueba: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estado de impresoras
     */
    public function getPrinterStatus()
    {
        try {
            $printers = \App\Models\PrinterSetting::where('is_enabled', true)->get();

            return response()->json([
                'success' => true,
                'printers' => $printers->map(function ($printer) {
                    return [
                        'id' => $printer->id,
                        'name' => $printer->name,
                        'model' => $printer->model,
                        'connection_type' => $printer->connection_type,
                        'is_active' => $printer->is_active,
                        'status' => $printer->status,
                        'last_test' => $printer->last_test,
                        'last_error' => $printer->last_error
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo estado de impresoras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprimir ticket de apertura de caja
     */
    public function printCashOpenTicket(Request $request)
    {
        try {
            $request->validate([
                'initial_amount' => 'required|numeric|min:0'
            ]);

            $content = $this->generateCashOpenContent($request->initial_amount);
            
            // Usar el método de red de la impresora activa
            $printer = \App\Models\PrinterSetting::where('is_active', true)->first();
            
            if (!$printer) {
                throw new \Exception('No hay impresora configurada');
            }

            // Usar el servicio de impresión directamente
            $service = new ThermalPrintService();
            
            // Como no tenemos una venta, creamos el contenido manualmente
            switch ($printer->connection_type) {
                case 'network':
                    $this->printViaNetwork($content, $printer);
                    break;
                default:
                    throw new \Exception('Tipo de conexión no soportado para apertura de caja');
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket de apertura impreso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error imprimiendo apertura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar contenido de apertura de caja
     */
    protected function generateCashOpenContent($initialAmount)
    {
        $width = 32;
        $content = '';

        $content .= "\x1B\x40"; // Inicializar
        $content .= $this->centerText("TOCHIS", $width);
        $content .= $this->centerText("APERTURA DE CAJA", $width);
        $content .= $this->centerText(str_repeat("=", $width), $width);
        $content .= $this->leftRightText("Fecha:", now()->format('d/m/Y H:i:s'), $width);
        $content .= $this->leftRightText("Cajero:", auth()->user()->name, $width);
        $content .= $this->centerText(str_repeat("-", $width), $width);
        $content .= $this->leftRightText("Monto inicial:", sprintf("$%.2f", $initialAmount), $width);
        $content .= $this->centerText(str_repeat("=", $width), $width);
        $content .= $this->centerText("Caja abierta", $width);
        $content .= "\n\n";

        return $content;
    }

    // Métodos auxiliares copiados del servicio
    protected function centerText($text, $width)
    {
        $len = strlen($text);
        if ($len >= $width) {
            return substr($text, 0, $width) . "\n";
        }
        $padding = floor(($width - $len) / 2);
        return str_repeat(' ', $padding) . $text . "\n";
    }

    protected function leftRightText($left, $right, $width)
    {
        $left = strlen($left) > $width - strlen($right) - 1 ? substr($left, 0, $width - strlen($right) - 4) . '...' : $left;
        $spaces = $width - strlen($left) - strlen($right);
        return $left . str_repeat(' ', max(1, $spaces)) . $right . "\n";
    }

    protected function printViaNetwork($content, $printer)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        if (!$socket) {
            throw new \Exception('No se pudo crear socket de red');
        }

        $result = socket_connect($socket, $printer->connection_string, $printer->port ?? 9100);
        
        if (!$result) {
            socket_close($socket);
            throw new \Exception("No se pudo conectar a la impresora");
        }

        socket_write($socket, $content, strlen($content));
        socket_close($socket);

        return true;
    }
}