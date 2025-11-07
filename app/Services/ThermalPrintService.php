<?php

namespace App\Services;

use App\Models\PrinterSetting;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class ThermalPrintService
{
    protected $printer;

    public function __construct()
    {
        // Obtener impresora activa
        $this->printer = PrinterSetting::where('is_active', true)
                                       ->where('is_enabled', true)
                                       ->first();
    }

    /**
     * Imprimir ticket de venta
     */
    public function printSaleTicket(Sale $sale)
    {
        try {
            if (!$this->printer) {
                throw new \Exception('No hay impresora térmica configurada');
            }

            Log::info('Iniciando impresión de ticket', [
                'sale_id' => $sale->id,
                'printer' => $this->printer->name
            ]);

            // Generar contenido del ticket
            $ticketContent = $this->generateTicketContent($sale);

            // Imprimir según el tipo de conexión
            switch ($this->printer->connection_type) {
                case 'network':
                    return $this->printViaNetwork($ticketContent);
                case 'usb':
                    return $this->printViaUSB($ticketContent);
                case 'serial':
                    return $this->printViaSerial($ticketContent);
                default:
                    throw new \Exception('Tipo de conexión no soportado: ' . $this->printer->connection_type);
            }

        } catch (\Exception $e) {
            Log::error('Error imprimiendo ticket: ' . $e->getMessage(), [
                'sale_id' => $sale->id,
                'printer_id' => $this->printer->id ?? null
            ]);

            // Actualizar estado de error en la impresora
            if ($this->printer) {
                $this->printer->update([
                    'status' => 'error',
                    'last_error' => $e->getMessage()
                ]);
            }

            throw $e;
        }
    }

    /**
     * Generar contenido del ticket
     */
    protected function generateTicketContent(Sale $sale)
    {
        $width = $this->printer->characters_per_line ?? 32;
        $content = '';

        // Comandos ESC/POS para inicializar
        $content .= "\x1B\x40"; // ESC @ - Inicializar impresora
        
        // Header - Logo/Nombre del restaurante
        $content .= $this->centerText("TOCHIS", $width);
        $content .= $this->centerText("Restaurante Familiar", $width);
        $content .= $this->centerText(str_repeat("=", $width), $width);
        $content .= "\n";

        // Información de la venta
        $content .= $this->leftRightText("Ticket: " . $sale->sale_number, "", $width);
        $content .= $this->leftRightText("Fecha: " . $sale->created_at->format('d/m/Y'), 
                                        $sale->created_at->format('H:i:s'), $width);
        $content .= $this->leftRightText("Cajero: " . ($sale->user->name ?? 'Sistema'), "", $width);
        
        if ($sale->order_number) {
            $content .= $this->leftRightText("Orden: #" . $sale->order_number, "", $width);
        }
        
        $content .= $this->centerText(str_repeat("-", $width), $width);

        // Detalles de productos
        $content .= $this->leftRightText("PRODUCTO", "TOTAL", $width);
        $content .= $this->centerText(str_repeat("-", $width), $width);

        foreach ($sale->saleDetails as $detail) {
            // Nombre del producto
            $productName = $this->truncateText($detail->product_name, $width - 8);
            $content .= $productName . "\n";
            
            // Cantidad x Precio = Total
            $line = sprintf("%d x $%.2f", $detail->quantity, $detail->product_price);
            $total = sprintf("$%.2f", $detail->subtotal);
            $content .= $this->leftRightText($line, $total, $width);

            // Opciones/especialidades si existen
            if ($detail->options->count() > 0) {
                foreach ($detail->options as $option) {
                    $optionText = "  + " . $this->truncateText($option->name, $width - 10);
                    if ($option->price > 0) {
                        $optionText = $this->leftRightText($optionText, sprintf("+$%.2f", $option->price), $width);
                    }
                    $content .= $optionText . "\n";
                }
            }
        }

        $content .= $this->centerText(str_repeat("-", $width), $width);

        // Totales
        $content .= $this->leftRightText("Subtotal:", sprintf("$%.2f", $sale->subtotal), $width);
        
        if ($sale->discount > 0) {
            $content .= $this->leftRightText("Descuento:", sprintf("-$%.2f", $sale->discount), $width);
        }
        
        if ($sale->tax > 0) {
            $content .= $this->leftRightText("Impuesto:", sprintf("$%.2f", $sale->tax), $width);
        }

        $content .= $this->centerText(str_repeat("=", $width), $width);
        $content .= $this->leftRightText("TOTAL:", sprintf("$%.2f", $sale->total), $width);
        $content .= $this->centerText(str_repeat("=", $width), $width);

        // Información de pago
        $paymentMethod = $this->getPaymentMethodText($sale->payment_method);
        $content .= $this->leftRightText("Pago: " . $paymentMethod, 
                                        sprintf("$%.2f", $sale->paid_amount), $width);
        
        if ($sale->change_amount > 0) {
            $content .= $this->leftRightText("Cambio:", sprintf("$%.2f", $sale->change_amount), $width);
        }

        // Información adicional para pagos con tarjeta
        if ($sale->payment_method === 'card' && $sale->card_installments) {
            $content .= $this->leftRightText("Cuotas:", $sale->card_installments . "x", $width);
        }

        $content .= "\n";

        // Footer
        $content .= $this->centerText("¡Gracias por su preferencia!", $width);
        $content .= $this->centerText("Vuelva pronto", $width);
        
        if ($sale->notes) {
            $content .= "\n";
            $content .= $this->centerText("Notas:", $width);
            $content .= $this->wrapText($sale->notes, $width);
        }

        $content .= "\n\n";

        // Comandos de corte (si está habilitado)
        if ($this->printer->auto_cut) {
            $content .= "\x1D\x56\x42\x00"; // GS V B - Corte parcial
        }

        // Abrir cajón (si está habilitado)
        if ($this->printer->cash_drawer) {
            $content .= "\x1B\x70\x00\x19\x64"; // ESC p - Abrir cajón
        }

        return $content;
    }

    /**
     * Imprimir vía red (TCP/IP)
     */
    protected function printViaNetwork($content)
    {
        $connection = $this->printer->connection_string;
        $port = $this->printer->port ?? 9100;

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
        if (!$socket) {
            throw new \Exception('No se pudo crear socket de red');
        }

        $result = socket_connect($socket, $connection, $port);
        
        if (!$result) {
            socket_close($socket);
            throw new \Exception("No se pudo conectar a la impresora en {$connection}:{$port}");
        }

        $bytesWritten = socket_write($socket, $content, strlen($content));
        socket_close($socket);

        if ($bytesWritten === false) {
            throw new \Exception('Error enviando datos a la impresora');
        }

        Log::info('Ticket impreso vía red', [
            'printer' => $this->printer->name,
            'bytes_sent' => $bytesWritten
        ]);

        return true;
    }

    /**
     * Imprimir vía USB (usando lp en Linux o copy en Windows)
     */
    protected function printViaUSB($content)
    {
        $printerPath = $this->printer->connection_string;

        // Para Windows
        if (PHP_OS_FAMILY === 'Windows') {
            $tempFile = tempnam(sys_get_temp_dir(), 'thermal_print_');
            file_put_contents($tempFile, $content);
            
            $command = "copy /B \"{$tempFile}\" \"{$printerPath}\"";
            exec($command, $output, $returnCode);
            
            unlink($tempFile);
            
            if ($returnCode !== 0) {
                throw new \Exception('Error imprimiendo vía USB: ' . implode(' ', $output));
            }
        } else {
            // Para Linux/Unix
            $command = "echo " . escapeshellarg($content) . " | lp -d " . escapeshellarg($printerPath);
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('Error imprimiendo vía USB: ' . implode(' ', $output));
            }
        }

        Log::info('Ticket impreso vía USB', [
            'printer' => $this->printer->name,
            'path' => $printerPath
        ]);

        return true;
    }

    /**
     * Imprimir vía puerto serie
     */
    protected function printViaSerial($content)
    {
        $port = $this->printer->connection_string;
        
        // En Windows usar COM ports, en Linux usar /dev/ttyUSB
        if (PHP_OS_FAMILY === 'Windows') {
            $handle = fopen($port, 'w');
        } else {
            $handle = fopen($port, 'w');
        }
        
        if (!$handle) {
            throw new \Exception("No se pudo abrir el puerto serie: {$port}");
        }

        $bytesWritten = fwrite($handle, $content);
        fclose($handle);

        if ($bytesWritten === false) {
            throw new \Exception('Error escribiendo al puerto serie');
        }

        Log::info('Ticket impreso vía serie', [
            'printer' => $this->printer->name,
            'port' => $port,
            'bytes_sent' => $bytesWritten
        ]);

        return true;
    }

    /**
     * Centrar texto
     */
    protected function centerText($text, $width)
    {
        $len = strlen($text);
        if ($len >= $width) {
            return substr($text, 0, $width) . "\n";
        }
        $padding = floor(($width - $len) / 2);
        return str_repeat(' ', $padding) . $text . "\n";
    }

    /**
     * Texto alineado a izquierda y derecha
     */
    protected function leftRightText($left, $right, $width)
    {
        $left = $this->truncateText($left, $width - strlen($right) - 1);
        $spaces = $width - strlen($left) - strlen($right);
        return $left . str_repeat(' ', max(1, $spaces)) . $right . "\n";
    }

    /**
     * Truncar texto
     */
    protected function truncateText($text, $maxLength)
    {
        return strlen($text) > $maxLength ? substr($text, 0, $maxLength - 3) . '...' : $text;
    }

    /**
     * Envolver texto
     */
    protected function wrapText($text, $width)
    {
        return wordwrap($text, $width, "\n", true) . "\n";
    }

    /**
     * Obtener texto del método de pago
     */
    protected function getPaymentMethodText($method)
    {
        $methods = [
            'cash' => 'Efectivo',
            'card' => 'Tarjeta',
            'transfer' => 'Transferencia'
        ];

        return $methods[$method] ?? $method;
    }

    /**
     * Probar impresora
     */
    public function testPrinter()
    {
        try {
            if (!$this->printer) {
                throw new \Exception('No hay impresora configurada');
            }

            $testContent = $this->generateTestContent();
            
            switch ($this->printer->connection_type) {
                case 'network':
                    $result = $this->printViaNetwork($testContent);
                    break;
                case 'usb':
                    $result = $this->printViaUSB($testContent);
                    break;
                case 'serial':
                    $result = $this->printViaSerial($testContent);
                    break;
                default:
                    throw new \Exception('Tipo de conexión no soportado');
            }

            // Actualizar estado exitoso
            $this->printer->update([
                'status' => 'online',
                'last_test' => now(),
                'last_error' => null
            ]);

            return true;

        } catch (\Exception $e) {
            // Actualizar estado de error
            if ($this->printer) {
                $this->printer->update([
                    'status' => 'error',
                    'last_error' => $e->getMessage()
                ]);
            }
            
            throw $e;
        }
    }

    /**
     * Generar contenido de prueba
     */
    protected function generateTestContent()
    {
        $width = $this->printer->characters_per_line ?? 32;
        $content = '';

        $content .= "\x1B\x40"; // Inicializar
        $content .= $this->centerText("PRUEBA DE IMPRESORA", $width);
        $content .= $this->centerText("TOCHIS POS", $width);
        $content .= $this->centerText(str_repeat("=", $width), $width);
        $content .= $this->leftRightText("Fecha:", now()->format('d/m/Y H:i:s'), $width);
        $content .= $this->leftRightText("Impresora:", $this->printer->name, $width);
        $content .= $this->leftRightText("Modelo:", $this->printer->model, $width);
        $content .= $this->leftRightText("Conexión:", $this->printer->connection_type, $width);
        $content .= $this->centerText(str_repeat("-", $width), $width);
        $content .= $this->centerText("Prueba exitosa!", $width);
        $content .= "\n\n";

        if ($this->printer->auto_cut) {
            $content .= "\x1D\x56\x42\x00";
        }

        return $content;
    }
}