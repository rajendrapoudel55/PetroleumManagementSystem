<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        Stock::create([
            'fuel_type' => 'Petrol',
            'fuel_code' => 'MS',
            'current_quantity' => 5000,
            'unit_price' => 105.50,
            'total_value' => 527500,
        ]);

        Stock::create([
            'fuel_type' => 'Diesel',
            'fuel_code' => 'HSD',
            'current_quantity' => 8000,
            'unit_price' => 98.75,
            'total_value' => 790000,
        ]);

        Stock::create([
            'fuel_type' => 'Lubricants',
            'fuel_code' => 'LUB',
            'current_quantity' => 500,
            'unit_price' => 450.00,
            'total_value' => 225000,
        ]);
    }
}
