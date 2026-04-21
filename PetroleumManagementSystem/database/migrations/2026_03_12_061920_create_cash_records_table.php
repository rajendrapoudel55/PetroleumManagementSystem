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
        Schema::create('cash_records', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->integer('qty_1000')->default(0);
            $table->integer('qty_500')->default(0);
            $table->integer('qty_100')->default(0);
            $table->integer('qty_50')->default(0);
            $table->integer('qty_20')->default(0);
            $table->integer('qty_10')->default(0);
            $table->integer('qty_5')->default(0);
            $table->decimal('total_cash', 12, 2)->default(0);
            $table->decimal('cheque_amount', 12, 2)->default(0);
            $table->decimal('net_cash', 12, 2)->default(0);
            $table->decimal('ic_amount', 12, 2)->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('difference', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_records');
    }
};
