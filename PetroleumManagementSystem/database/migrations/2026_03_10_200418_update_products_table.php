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
        Schema::table('products', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('products', 'product_name')) {
                $table->string('product_name');
            }
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique();
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->decimal('stock_quantity', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->default('pc');
            }
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2);
            }
            if (!Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 10, 2);
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->decimal('min_stock', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'last_purchase')) {
                $table->timestamp('last_purchase')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'sku',
                'category',
                'stock_quantity',
                'unit',
                'cost_price',
                'selling_price',
                'min_stock',
                'last_purchase',
            ]);
        });
    }
};
