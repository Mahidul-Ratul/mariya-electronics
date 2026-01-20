<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'customer_id',
        'customer_name',
        'customer_address', 
        'customer_mobile',
        'guarantor_name',
        'guarantor_mobile',
        'product_id',
        'products_data',
        'quantity',
        'unit_price',
        'discount_amount',
        'discount_percentage',
        'subtotal',
        'total_amount',
        'paid_amount',
        'payment_type',
        'installment_months',
        'status',
        'sale_date',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'installment_months' => 'integer',
        'sale_date' => 'date',
        'products_data' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sale) {
            if (empty($sale->sale_number)) {
                $sale->sale_number = 'SALE-' . date('Y') . '-' . str_pad(Sale::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeInstallment($query)
    {
        return $query->where('payment_type', 'installment');
    }

    public function scopeCash($query)
    {
        return $query->where('payment_type', 'cash');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    /**
     * Get all products from products_data JSON
     */
    public function getProductsListAttribute()
    {
        if (!$this->products_data) {
            return [];
        }

        $products = is_array($this->products_data) ? $this->products_data : json_decode($this->products_data, true);
        return $products ?? [];
    }

    /**
     * Get total quantity from all products
     */
    public function getTotalQuantityAttribute()
    {
        if ($this->quantity !== null) {
            return $this->quantity;
        }

        $products = $this->products_list;
        return array_sum(array_column($products, 'quantity'));
    }

    /**
     * Get number of products
     */
    public function getProductsCountAttribute()
    {
        return count($this->products_list);
    }}