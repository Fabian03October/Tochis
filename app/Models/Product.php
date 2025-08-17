<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'price',
        'cost',
        'stock',
        'min_stock',
        'image',
        'is_active',
        'is_food',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
        'is_food' => 'boolean',
    ];

    /**
     * Get the category that owns this product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get sale details for this product
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function observationOptions()
    {
        return $this->hasMany(ProductOption::class)->where('type', 'observation');
    }

    public function specialtyOptions()
    {
        return $this->hasMany(ProductOption::class)->where('type', 'specialty');
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get products with low stock
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    /**
     * Check if product has low stock
     */
    public function hasLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Decrease stock when sold
     */
    public function decreaseStock(int $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            return $this->save();
        }
        return false;
    }

    /**
     * Increase stock when restocked
     */
    public function increaseStock(int $quantity): bool
    {
        $this->stock += $quantity;
        return $this->save();
    }
}
