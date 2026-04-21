<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'email',
        'company_name',
        'name',
        'phone_number',
        'address',
    ];
}