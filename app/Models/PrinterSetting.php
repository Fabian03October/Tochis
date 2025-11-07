<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrinterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'connection_type',
        'connection_string',
        'port',
        'paper_width',
        'auto_cut',
        'cash_drawer',
        'characters_per_line',
        'print_settings',
        'is_active',
        'is_enabled',
        'last_test',
        'status',
        'last_error'
    ];

    protected function casts(): array
    {
        return [
            'auto_cut' => 'boolean',
            'cash_drawer' => 'boolean',
            'is_active' => 'boolean',
            'is_enabled' => 'boolean',
            'last_test' => 'datetime',
            'print_settings' => 'array',
        ];
    }

    /**
     * Modelos de impresora soportados
     */
    public static function getSupportedModels()
    {
        return [
            'epson_tm_t20' => 'Epson TM-T20',
            'epson_tm_t88' => 'Epson TM-T88',
            'star_tsp100' => 'Star TSP100',
            'star_tsp650' => 'Star TSP650',
            'citizen_ct_s310' => 'Citizen CT-S310',
            'bixolon_srp_350' => 'Bixolon SRP-350',
            'generic_esc_pos' => 'Genérica ESC/POS',
        ];
    }

    /**
     * Tipos de conexión disponibles
     */
    public static function getConnectionTypes()
    {
        return [
            'usb' => 'USB',
            'network' => 'Red (Ethernet/WiFi)',
            'bluetooth' => 'Bluetooth',
            'serial' => 'Puerto Serial'
        ];
    }

    /**
     * Anchos de papel soportados
     */
    public static function getPaperWidths()
    {
        return [
            '58mm' => '58mm (2")',
            '80mm' => '80mm (3")'
        ];
    }

    /**
     * Obtener la impresora activa
     */
    public static function getActive()
    {
        return static::where('is_active', true)
                    ->where('is_enabled', true)
                    ->first();
    }

    /**
     * Activar esta impresora (desactivar las demás)
     */
    public function activate()
    {
        // Desactivar todas las impresoras
        static::where('is_active', true)->update(['is_active' => false]);
        
        // Activar esta impresora
        $this->update(['is_active' => true]);
        
        return $this;
    }

    /**
     * Probar conexión con la impresora
     */
    public function testConnection()
    {
        try {
            // Aquí iría la lógica de prueba según el tipo de conexión
            $result = $this->performConnectionTest();
            
            $this->update([
                'status' => $result ? 'connected' : 'disconnected',
                'last_test' => now(),
                'last_error' => $result ? null : 'Test de conexión falló'
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            $this->update([
                'status' => 'error',
                'last_test' => now(),
                'last_error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Realizar test específico según tipo de conexión
     */
    private function performConnectionTest()
    {
        switch ($this->connection_type) {
            case 'usb':
                return $this->testUSBConnection();
            case 'network':
                return $this->testNetworkConnection();
            case 'bluetooth':
                return $this->testBluetoothConnection();
            case 'serial':
                return $this->testSerialConnection();
            default:
                return false;
        }
    }

    /**
     * Test conexión USB
     */
    private function testUSBConnection()
    {
        // Lógica para probar conexión USB
        // Esto dependería del driver específico
        return true; // Placeholder
    }

    /**
     * Test conexión de red
     */
    private function testNetworkConnection()
    {
        if (!$this->connection_string || !$this->port) {
            return false;
        }

        $connection = @fsockopen($this->connection_string, $this->port, $errno, $errstr, 5);
        
        if ($connection) {
            fclose($connection);
            return true;
        }
        
        return false;
    }

    /**
     * Test conexión Bluetooth
     */
    private function testBluetoothConnection()
    {
        // Lógica para probar conexión Bluetooth
        return true; // Placeholder
    }

    /**
     * Test conexión Serial
     */
    private function testSerialConnection()
    {
        // Lógica para probar conexión Serial
        return true; // Placeholder
    }

    /**
     * Obtener configuración de caracteres por línea según ancho de papel
     */
    public function getCharactersPerLineForWidth()
    {
        return match($this->paper_width) {
            '58mm' => 32,
            '80mm' => 48,
            default => 48
        };
    }
}
