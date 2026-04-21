<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRecord extends Model
{
    protected $fillable = [
        'date', 'qty_1000', 'qty_500', 'qty_100', 'qty_50', 'qty_20', 'qty_10', 'qty_5',
        'total_cash', 'cheque_amount', 'net_cash', 'ic_amount', 'total_sales', 'difference',
    ];
}
