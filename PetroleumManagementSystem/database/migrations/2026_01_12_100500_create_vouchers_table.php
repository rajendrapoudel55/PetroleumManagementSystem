<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_number')->unique();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');

            $table->string('payment_mode');
            $table->string('party_name');
            $table->text('address');
            $table->string('phone_number');
            $table->string('tax_number');

            $table->string('vehicle_number');

            // Fuel properties (optional)
            $table->decimal('density', 8, 2)->nullable();
            $table->decimal('temperature', 6, 2)->nullable();
            $table->string('fbp_chamber')->nullable();

            // Storage chambers (JSON string)
            $table->json('chambers');

            // Amounts
            $table->decimal('subtotal', 15, 2);
            $table->decimal('extra_charge', 10, 2)->default(0);
            $table->decimal('rounding', 10, 2)->default(0);
            $table->decimal('before_tax_total', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);

            $table->string('status')->default('completed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_vouchers');
    }
};
