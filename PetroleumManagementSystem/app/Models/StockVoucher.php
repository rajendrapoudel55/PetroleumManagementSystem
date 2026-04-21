<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockVoucher extends Model
{
    use HasFactory;

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
        'status'
    ];
}
