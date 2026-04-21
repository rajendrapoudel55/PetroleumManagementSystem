<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'sku',
        'category',
        'stock_quantity',
        'unit',
        'cost_price',
        'selling_price',
        'min_stock',
        'last_purchase',
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'last_purchase' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
