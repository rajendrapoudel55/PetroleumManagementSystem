<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_voucher_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_voucher_id')
                  ->constrained('stock_vouchers')
                  ->onDelete('cascade');

            $table->string('fuel_type'); // MS, HSD, LUB
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_rate', 10, 2);
            $table->decimal('discount', 6, 2)->default(0);
            $table->decimal('amount', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_voucher_items');
    }
};
