<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrinterController extends Controller
{
    /**
     * Display a listing of the printers.
     */
    public function index()
    {
        $printers = PrinterSetting::orderBy('is_active', 'desc')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('admin.printers.index', compact('printers'));
    }

    /**
     * Show the form for creating a new printer.
     */
    public function create()
    {
        return view('admin.printers.create');
    }

    /**
     * Store a newly created printer in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'model' => 'required|string',
            'connection_type' => 'required|in:usb,network,bluetooth,serial',
            'connection_string' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'paper_width' => 'required|in:58mm,80mm',
            'auto_cut' => 'boolean',
            'cash_drawer' => 'boolean',
            'characters_per_line' => 'required|integer|min:20|max:80',
            'is_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();
        $data['auto_cut'] = $request->has('auto_cut');
        $data['cash_drawer'] = $request->has('cash_drawer');
        $data['is_enabled'] = $request->has('is_enabled');
        $data['status'] = 'unknown';

        PrinterSetting::create($data);

        return redirect()->route('admin.printers.index')
                        ->with('success', 'Impresora configurada exitosamente.');
    }

    /**
     * Display the specified printer.
     */
    public function show(PrinterSetting $printer)
    {
        return view('admin.printers.show', compact('printer'));
    }

    /**
     * Show the form for editing the specified printer.
     */
    public function edit(PrinterSetting $printer)
    {
        return view('admin.printers.edit', compact('printer'));
    }

    /**
     * Update the specified printer in storage.
     */
    public function update(Request $request, PrinterSetting $printer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'model' => 'required|string',
            'connection_type' => 'required|in:usb,network,bluetooth,serial',
            'connection_string' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'paper_width' => 'required|in:58mm,80mm',
            'auto_cut' => 'boolean',
            'cash_drawer' => 'boolean',
            'characters_per_line' => 'required|integer|min:20|max:80',
            'is_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();
        $data['auto_cut'] = $request->has('auto_cut');
        $data['cash_drawer'] = $request->has('cash_drawer');
        $data['is_enabled'] = $request->has('is_enabled');

        $printer->update($data);

        return redirect()->route('admin.printers.index')
                        ->with('success', 'Configuración de impresora actualizada exitosamente.');
    }

    /**
     * Remove the specified printer from storage.
     */
    public function destroy(PrinterSetting $printer)
    {
        if ($printer->is_active) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar la impresora activa.');
        }

        $printer->delete();

        return redirect()->route('admin.printers.index')
                        ->with('success', 'Impresora eliminada exitosamente.');
    }

    /**
     * Activate a printer (set as default).
     */
    public function activate(PrinterSetting $printer)
    {
        if (!$printer->is_enabled) {
            return redirect()->back()
                           ->with('error', 'No se puede activar una impresora deshabilitada.');
        }

        $printer->activate();

        return redirect()->back()
                        ->with('success', 'Impresora activada como predeterminada.');
    }

    /**
     * Test printer connection.
     */
    public function test(PrinterSetting $printer)
    {
        $result = $printer->testConnection();

        if ($result) {
            return redirect()->back()
                           ->with('success', 'Conexión con la impresora exitosa.');
        } else {
            return redirect()->back()
                           ->with('error', 'Error de conexión: ' . ($printer->last_error ?? 'Desconocido'));
        }
    }

    /**
     * Print a test receipt.
     */
    public function printTest(PrinterSetting $printer)
    {
        try {
            // Aquí iría la lógica para imprimir un ticket de prueba
            $this->generateTestReceipt($printer);

            return redirect()->back()
                           ->with('success', 'Ticket de prueba enviado a la impresora.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al imprimir: ' . $e->getMessage());
        }
    }

    /**
     * Generate and send a test receipt to the printer.
     */
    private function generateTestReceipt(PrinterSetting $printer)
    {
        $testContent = $this->buildTestReceiptContent($printer);
        
        // Aquí implementarías el envío real a la impresora
        // según el tipo de conexión y modelo
        
        return true; // Placeholder
    }

    /**
     * Build test receipt content.
     */
    private function buildTestReceiptContent(PrinterSetting $printer)
    {
        $width = $printer->characters_per_line;
        $line = str_repeat('-', $width);
        
        return implode("\n", [
            str_pad('TOCHIS - TICKET DE PRUEBA', $width, ' ', STR_PAD_BOTH),
            $line,
            'Impresora: ' . $printer->name,
            'Modelo: ' . $printer->model,
            'Ancho: ' . $printer->paper_width,
            'Conexión: ' . $printer->connection_type,
            $line,
            'Fecha: ' . now()->format('d/m/Y H:i:s'),
            $line,
            'Test realizado exitosamente!',
            '',
            '',
            ''
        ]);
    }

    /**
     * Get printer configuration for API/AJAX.
     */
    public function getConfig(PrinterSetting $printer)
    {
        return response()->json([
            'success' => true,
            'printer' => $printer,
            'supported_models' => PrinterSetting::getSupportedModels(),
            'connection_types' => PrinterSetting::getConnectionTypes(),
            'paper_widths' => PrinterSetting::getPaperWidths()
        ]);
    }
}
