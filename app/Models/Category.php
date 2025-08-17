<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
        'is_customizable',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_customizable' => 'boolean',
        ];
    }

    /**
     * Get products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products in this category
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get customization options for this category
     */
    public function customizationOptions()
    {
        return $this->belongsToMany(ProductCustomizationOption::class, 'category_customization_option');
    }

    /**
     * Get active customization options for this category
     */
    public function activeCustomizationOptions()
    {
        return $this->customizationOptions()->where('is_active', true)->orderBy('sort_order');
    }
}
