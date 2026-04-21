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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['payment', 'receive', 'expense', 'deposit']);
            $table->date('date');
            $table->string('vendor_name');
            $table->decimal('amount', 10, 2);
            $table->string('category');
            $table->enum('payment_method', ['cash', 'bank', 'cheque', 'card']);
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
