<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_type',
        'fuel_code',
        'current_quantity',
        'unit_price',
        'selling_price',
        'total_value',
    ];

    protected $casts = [
        'current_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];
}
