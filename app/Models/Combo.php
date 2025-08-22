<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'discount_amount',
        'is_active',
        'image',
        'min_items',
        'auto_suggest'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'auto_suggest' => 'boolean',
    ];

    /**
     * Productos que componen este combo
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'combo_products')
                    ->withPivot(['quantity', 'is_required', 'is_alternative'])
                    ->withTimestamps();
    }

    /**
     * Scope para combos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calcular el ahorro del combo
     */
    public function getSavingsAttribute()
    {
        return $this->original_price - $this->price;
    }

    /**
     * Porcentaje de descuento
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price > 0) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100, 2);
        }
        return 0;
    }

    /**
     * Verificar si un conjunto de productos coincide con este combo
     */
    public function matchesProducts($productIds)
    {
        $requiredProducts = $this->products()->wherePivot('is_required', true)->pluck('product_id')->toArray();
        $providedProducts = is_array($productIds) ? $productIds : [$productIds];
        
        // Verificar que al menos tenga los productos requeridos
        $hasRequiredProducts = empty(array_diff($requiredProducts, $providedProducts));
        
        // Verificar que tenga al menos el mínimo de productos
        $hasMinimumItems = count($providedProducts) >= $this->min_items;
        
        return $hasRequiredProducts && $hasMinimumItems;
    }

    /**
     * Obtener el nivel de coincidencia con productos del carrito
     */
    public function getMatchLevel($cartProducts)
    {
        $comboProductIds = $this->products->pluck('id')->toArray();
        $cartProductIds = collect($cartProducts)->pluck('id')->toArray();
        
        // Evitar división por cero si el combo no tiene productos
        if (empty($comboProductIds)) {
            return [
                'percentage' => 0,
                'matched_products' => 0,
                'total_products' => 0,
                'missing_products' => []
            ];
        }
        
        $matches = array_intersect($comboProductIds, $cartProductIds);
        $matchPercentage = count($matches) / count($comboProductIds) * 100;
        
        return [
            'percentage' => round($matchPercentage, 2),
            'matched_products' => count($matches),
            'total_products' => count($comboProductIds),
            'missing_products' => array_diff($comboProductIds, $cartProductIds)
        ];
    }
}
