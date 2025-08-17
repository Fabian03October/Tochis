<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'initial_amount',
        'sales_amount',
        'final_amount',
        'expected_amount',
        'difference',
        'total_sales',
        'notes',
        'opened_at',
        'closed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'initial_amount' => 'decimal:2',
            'sales_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'expected_amount' => 'decimal:2',
            'difference' => 'decimal:2',
            'total_sales' => 'integer',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Get the user (cashier) who made this cash cut
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function incomes()
    {
        return $this->hasMany(CashMovement::class)->where('type', 'income');
    }

    public function expenses()
    {
        return $this->hasMany(CashMovement::class)->where('type', 'expense');
    }

    /**
     * Calculate expected amount and difference
     */
    public function calculateAmounts()
    {
        $this->expected_amount = $this->initial_amount + $this->sales_amount;
        $this->difference = $this->final_amount - $this->expected_amount;
    }

    /**
     * Scope to get open cash cuts
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to get closed cash cuts
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Close the cash cut
     */
    public function close($finalAmount, $notes = null)
    {
        $this->final_amount = $finalAmount;
        $this->notes = $notes;
        $this->closed_at = now();
        $this->status = 'closed';
        $this->calculateAmounts();
        $this->save();
    }
}
