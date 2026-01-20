<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'brand',
        'description',
        'image',
        'cost_price',
        'selling_price',
        'max_discount',
        'stock_quantity',
        'unit',
        'category',
        'category_id',
        'brand_id',
        'warranty_period',
        'is_active'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getFormattedSellingPriceAttribute()
    {
        return number_format($this->selling_price, 2);
    }

    public function getFormattedCostPriceAttribute()
    {
        return number_format($this->cost_price, 2);
    }
}
