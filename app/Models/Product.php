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
        'compare_price',
        'cost',
        'image',
        'stock',
        'min_stock',
        'manage_stock',
        'is_active',
        'is_food',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'manage_stock' => 'boolean',
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
        return $this->hasMany(Platilloption::class);
    }

    public function observationOptions()
    {
        return $this->hasMany(Platilloption::class)->where('type', 'observation');
    }

    public function specialtyOptions()
    {
        return $this->hasMany(Platilloption::class)->where('type', 'specialty');
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
        return $query->where('manage_stock', true)
                    ->whereColumn('stock', '<=', 'min_stock')
                    ->orWhere(function($q) {
                        $q->where('manage_stock', true)
                          ->where('stock', '<=', 10);
                    });
    }
}
