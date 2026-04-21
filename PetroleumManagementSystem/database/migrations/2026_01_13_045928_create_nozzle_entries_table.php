<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nozzle_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('diesel_n1_opening', 10, 2)->default(0);
            $table->decimal('diesel_n1_closing', 10, 2)->default(0);
            $table->decimal('diesel_n2_opening', 10, 2)->default(0);
            $table->decimal('diesel_n2_closing', 10, 2)->default(0);
            $table->decimal('petrol_n1_opening', 10, 2)->default(0);
            $table->decimal('petrol_n1_closing', 10, 2)->default(0);
            $table->decimal('petrol_n2_opening', 10, 2)->default(0);
            $table->decimal('petrol_n2_closing', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nozzle_entries');
    }
};