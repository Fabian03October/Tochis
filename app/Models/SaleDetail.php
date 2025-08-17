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
        'product_name',
        'product_price',
        'quantity',
        'discount',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'product_price' => 'decimal:2',
            'quantity' => 'integer',
            'discount' => 'decimal:2',
            'subtotal' => 'decimal:2',
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
