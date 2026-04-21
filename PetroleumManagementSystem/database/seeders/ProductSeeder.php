<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stock;

class ProductSeeder extends Seeder
{
    /**
     * Seed fuel stock into the stocks table (source of truth for fuels).
     */
    public function run(): void
    {
        Stock::firstOrCreate(
            ['fuel_code' => 'MS'],
            [
                'fuel_type'        => 'Petrol',
                'current_quantity' => 5000,
                'unit_price'       => 120,
                'total_value'      => 5000 * 120,
            ]
        );

        Stock::firstOrCreate(
            ['fuel_code' => 'HSD'],
            [
                'fuel_type'        => 'Diesel',
                'current_quantity' => 3500,
                'unit_price'       => 115,
                'total_value'      => 3500 * 115,
            ]
        );
    }
}
