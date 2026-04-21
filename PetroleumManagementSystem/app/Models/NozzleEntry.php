<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NozzleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'diesel_n1_opening',
        'diesel_n1_closing',
        'diesel_n2_opening',
        'diesel_n2_closing',
        'diesel_n3_opening',
        'diesel_n3_closing',
        'diesel_n4_opening',
        'diesel_n4_closing',
        'petrol_n1_opening',
        'petrol_n1_closing',
        'petrol_n2_opening',
        'petrol_n2_closing',
        'petrol_n3_opening',
        'petrol_n3_closing',
        'petrol_n4_opening',
        'petrol_n4_closing',
    ];
}
