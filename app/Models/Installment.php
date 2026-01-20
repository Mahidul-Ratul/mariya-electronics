<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'installment_sale_number',
        'customer_name',
        'customer_mobile',
        'customer_address',
        'customer_nid',
        'customer_nid_image',
        'customer_wife_name',
        'customer_wife_nid',
        'customer_wife_nid_image',
        'guarantor_name',
        'guarantor_mobile',
        'guarantor_address',
        'guarantor_nid',
        'guarantor_security_info',
        'guarantor_security_image',
        'products_data',
        'subtotal',
        'discount_amount',
        'total_amount',
        'down_payment',
        'total_installments',
        'monthly_installment',
        'sale_date',
        'installment_number',
        'amount',
        'fine_amount',
        'due_date',
        'paid_date',
        'paid_amount',
        'penalty_amount',
        'status',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'products_data' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'sale_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeDueSoon($query, $days = 7)
    {
        return $query->where('status', 'pending')
                    ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'paid' || $this->due_date >= now()) {
            return 0;
        }
        
        return now()->diffInDays($this->due_date);
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount + $this->penalty_amount - $this->paid_amount;
    }

    public function markAsPaid($amount, $paymentDate = null)
    {
        $this->update([
            'paid_amount' => $amount,
            'paid_date' => $paymentDate ?: now(),
            'status' => $amount >= $this->amount + $this->penalty_amount ? 'paid' : 'partial'
        ]);
    }

    public function addPenalty($amount)
    {
        $this->increment('penalty_amount', $amount);
        if ($this->status === 'pending') {
            $this->update(['status' => 'overdue']);
        }
    }
}
