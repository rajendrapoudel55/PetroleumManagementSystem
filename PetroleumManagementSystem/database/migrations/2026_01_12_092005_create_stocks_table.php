<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->string('fuel_type');      // Petrol, Diesel, Lubricant
            $table->string('fuel_code')->unique(); // MS, HSD, LUB

            $table->decimal('current_quantity', 12, 2)->default(0); // Liters
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
