<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxInvoice extends Model
{
    protected $fillable = [
        'bill_number', 'date', 'customer_name', 'phone', 'vehicle',
        'payment_method', 'transaction_no', 'items_json',
        'subtotal', 'VAT', 'gst', 'total',
    ];

    /**
     * Map 'gst' attribute to 'VAT' column for database compatibility
     */
    public function setGstAttribute($value)
    {
        $this->attributes['VAT'] = $value;
    }

    /**
     * Map 'VAT' column to 'gst' attribute when retrieving
     */
    public function getGstAttribute()
    {
        return $this->attributes['VAT'] ?? null;
    }
}
