<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_cut_id',
        'user_id',
        'type',
        'amount',
        'concept',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function cashCut()
    {
        return $this->belongsTo(CashCut::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
