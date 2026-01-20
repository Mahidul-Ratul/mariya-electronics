<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'installment_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('payment_date', now()->toDateString());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }
}
