<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockVoucherItem extends Model
{
    protected $fillable = [
        'stock_voucher_id',
        'fuel_type',
        'quantity',
        'unit_rate',
        'discount',
        'amount',
    ];

    public function voucher()
    {
        return $this->belongsTo(StockVoucher::class);
    }
}