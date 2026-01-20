<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'id_card_type',
        'id_card_number',
        'credit_limit',
        'is_blacklisted',
        'notes'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_blacklisted' => 'boolean',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function installments()
    {
        return $this->hasManyThrough(Installment::class, Sale::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Sale::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_blacklisted', false);
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address . ', ' . $this->city;
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        if ($this->zip_code) {
            $address .= ' - ' . $this->zip_code;
        }
        return $address;
    }

    public function getTotalDueAttribute()
    {
        return $this->installments()->where('status', 'pending')->sum('amount');
    }
}
