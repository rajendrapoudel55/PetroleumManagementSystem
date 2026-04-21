<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockVoucher extends Model
{
    protected $fillable = [
        'voucher_number',
        'invoice_number',
        'invoice_date',
        'payment_mode',
        'party_name',
        'address',
        'tax_number',
        'phone_number',
        'vehicle_number',
        'density',
        'temperature',
        'fbp_chamber',
        'chambers',
        'subtotal',
        'extra_charge',
        'rounding',
        'before_tax_total',
        'tax_amount',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'chambers' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(StockVoucherItem::class);
    }
}