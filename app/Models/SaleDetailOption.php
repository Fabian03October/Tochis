<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetailOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_detail_id',
        'product_option_id',
        'type',
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the sale detail that owns this option
     */
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class);
    }

    /**
     * Get the product customization option
     */
    public function productOption()
    {
        return $this->belongsTo(ProductCustomizationOption::class, 'product_option_id');
    }
}
