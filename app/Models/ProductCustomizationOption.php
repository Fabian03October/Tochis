<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationOption extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Scopes para filtrar por tipo
    public function scopeObservations($query)
    {
        return $query->where('type', 'observation');
    }

    public function scopeSpecialties($query)
    {
        return $query->where('type', 'specialty');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get categories that use this customization option
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_customization_option');
    }
}
