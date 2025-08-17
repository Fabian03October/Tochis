<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'discount_value',
        'apply_to',
        'applicable_items',
        'minimum_amount',
        'max_uses',
        'uses_count',
        'start_date',
        'end_date',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'applicable_items' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'minimum_amount' => 'decimal:2'
    ];

    // Relaciones
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_categories');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    // Métodos auxiliares para categorías y productos
    public function getCategoryIdsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }

    public function getProductIdsAttribute()
    {
        return $this->products->pluck('id')->toArray();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function($q) {
                        $q->whereNull('max_uses')
                          ->orWhereColumn('uses_count', '<', 'max_uses');
                    });
    }

    // Métodos auxiliares
    public function isActive()
    {
        return $this->is_active && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function canBeUsed()
    {
        return $this->isActive() && 
               (is_null($this->max_uses) || $this->uses_count < $this->max_uses);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->canBeUsed()) {
            return 0;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($amount * $this->discount_value) / 100;
        }

        return min($this->discount_value, $amount);
    }

    public function getRemainingTime()
    {
        if ($this->end_date < now()) {
            return 'Expirada';
        }

        return $this->end_date->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'Inactiva';
        }

        if ($this->start_date > now()) {
            return 'Programada';
        }

        if ($this->end_date < now()) {
            return 'Expirada';
        }

        if ($this->max_uses && $this->uses_count >= $this->max_uses) {
            return 'Agotada';
        }

        return 'Activa';
    }
}
