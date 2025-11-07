<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'combo_id',
        'product_name',
        'combo_name',
        'product_price',
        'combo_price',
        'quantity',
        'discount',
        'subtotal',
        'item_type',
        'selected_options',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'product_price' => 'decimal:2',
            'combo_price' => 'decimal:2',
            'quantity' => 'integer',
            'discount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'selected_options' => 'array',
        ];
    }

    /**
     * Get the sale that owns this detail
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product for this detail
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the combo for this detail
     */
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    /**
     * Calculate subtotal automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($saleDetail) {
            $saleDetail->subtotal = ($saleDetail->product_price * $saleDetail->quantity) - $saleDetail->discount;
        });
    }
}
