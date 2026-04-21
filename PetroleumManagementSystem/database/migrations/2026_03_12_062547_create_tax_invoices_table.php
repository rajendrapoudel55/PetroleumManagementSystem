<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->date('date');
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('payment_method');
            $table->string('transaction_no')->nullable();
            $table->text('items_json');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('VAT', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_invoices');
    }
};
