<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'order_number',
        'order_date',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'card_payment_reference',
        'card_installments',
        'card_payment_details',
        'status',
        'notes',
        'kitchen_status',
        'station_type',
        'kitchen_started_at',
        'kitchen_ready_at',
        'preparation_minutes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'card_payment_details' => 'array',
            'kitchen_started_at' => 'datetime',
            'kitchen_ready_at' => 'datetime',
            'paid_at' => 'datetime',
            'delivered_at' => 'datetime',
            'order_date' => 'date',
        ];
    }

    /**
     * Generate sale number and order number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $today = today();
            
            // Generar número de venta único
            if (empty($sale->sale_number)) {
                $sale->sale_number = 'SALE-' . $today->format('Ymd') . '-' . str_pad(static::whereDate('created_at', $today)->count() + 1, 4, '0', STR_PAD_LEFT);
            }
            
            // Generar número de orden consecutivo por día (reinicia cada día)
            if (empty($sale->order_number)) {
                // Buscar el último número de orden del día actual
                $lastOrder = static::where('order_date', $today)->max('order_number');
                $nextOrderNumber = ($lastOrder ?? 0) + 1;
                
                $sale->order_number = $nextOrderNumber;
                $sale->order_date = $today;
            }
            
            // Establecer estado inicial de cocina como pendiente
            if (empty($sale->kitchen_status)) {
                $sale->kitchen_status = 'pending';
            }
        });
        
        static::created(function ($sale) {
            // Después de crear la venta, determinar automáticamente si va a cocina o barra
            // y enviarla inmediatamente
            $sale->determineStationAndSend();
        });
    }

    /**
     * Get the user (cashier) who made this sale
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get sale details for this sale
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    /**
     * Scope to get sales from today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope to get sales from specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get completed sales
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get total items in this sale
     */
    public function getTotalItemsAttribute()
    {
        return $this->saleDetails->sum('quantity');
    }

    /**
     * Scopes para control de cocina
     */
    public function scopeInKitchen($query)
    {
        return $query->where('kitchen_status', 'in_kitchen');
    }

    public function scopeReady($query)
    {
        return $query->where('kitchen_status', 'ready');
    }

    public function scopeReceived($query)
    {
        return $query->where('kitchen_status', 'received');
    }

    public function scopeDelivered($query)
    {
        return $query->where('kitchen_status', 'delivered');
    }

    public function scopePendingKitchen($query)
    {
        return $query->where('kitchen_status', 'pending');
    }

    public function scopeInBar($query)
    {
        return $query->where('kitchen_status', 'in_bar');
    }

    /**
     * Determinar automáticamente si va a cocina o barra y enviar
     */
    public function determineStationAndSend()
    {
        // Cargar los detalles de venta con productos
        $this->load('saleDetails.product.category');
        
        // Determinar si la mayoría de productos son bebidas
        $totalProducts = $this->saleDetails->count();
        $drinkProducts = $this->saleDetails->filter(function ($detail) {
            // Buscar bebidas por nombre de categoría o producto
            $categoryName = strtolower($detail->product->category->name ?? '');
            $productName = strtolower($detail->product->name ?? '');
            
            return str_contains($categoryName, 'bebida') || 
                   str_contains($categoryName, 'drink') ||
                   str_contains($categoryName, 'barra') ||
                   str_contains($productName, 'jugo') ||
                   str_contains($productName, 'refresco') ||
                   str_contains($productName, 'agua') ||
                   str_contains($productName, 'cerveza') ||
                   str_contains($productName, 'vino') ||
                   str_contains($productName, 'café') ||
                   str_contains($productName, 'té');
        })->count();
        
        // Si más del 50% son bebidas, va a barra, sino a cocina
        $stationType = ($drinkProducts > $totalProducts / 2) ? 'bar' : 'kitchen';
        $status = ($stationType === 'bar') ? 'in_bar' : 'in_kitchen';
        
        $this->update([
            'station_type' => $stationType,
            'kitchen_status' => $status,
            'kitchen_started_at' => now()
        ]);
    }

    /**
     * Marcar orden como lista (desde cocina/barra)
     */
    public function markReady()
    {
        $this->update([
            'kitchen_status' => 'ready',
            'kitchen_ready_at' => now(),
            'preparation_minutes' => $this->kitchen_started_at 
                ? $this->kitchen_started_at->diffInMinutes(now()) 
                : null
        ]);
    }

    /**
     * Marcar orden como recibida por cajero
     */
    public function markReceived()
    {
        $this->update([
            'kitchen_status' => 'received'
        ]);
    }

    /**
     * Marcar orden como entregada al cliente
     */
    public function markDelivered()
    {
        $this->update([
            'kitchen_status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    /**
     * Obtener tiempo transcurrido en cocina
     */
    public function getKitchenTimeAttribute()
    {
        if (!$this->kitchen_started_at) {
            return 0;
        }

        $endTime = $this->kitchen_ready_at ?: now();
        return $this->kitchen_started_at->diffInMinutes($endTime);
    }

    /**
     * Obtener estado legible de cocina
     */
    public function getKitchenStatusTextAttribute()
    {
        return match($this->kitchen_status) {
            'pending' => 'Pendiente',
            'in_kitchen' => 'En Cocina',
            'in_bar' => 'En Barra',
            'ready' => 'Listo',
            'received' => 'Recibido',
            'delivered' => 'Entregado',
            default => 'Desconocido'
        };
    }

    /**
     * Obtener color del estado de cocina
     */
    public function getKitchenStatusColorAttribute()
    {
        return match($this->kitchen_status) {
            'pending' => 'secondary',
            'in_kitchen' => 'warning',
            'in_bar' => 'info',
            'ready' => 'success',
            'received' => 'primary',
            'delivered' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Obtener el nombre de la estación
     */
    public function getStationNameAttribute()
    {
        return match($this->station_type) {
            'kitchen' => 'Cocina',
            'bar' => 'Barra',
            default => 'Cocina'
        };
    }

    /**
     * Obtener nombre de orden formateado
     */
    public function getOrderDisplayNameAttribute()
    {
        return "Orden #{$this->order_number}";
    }

    /**
     * Scope para obtener órdenes por fecha específica
     */
    public function scopeByOrderDate($query, $date)
    {
        return $query->where('order_date', $date);
    }
}
