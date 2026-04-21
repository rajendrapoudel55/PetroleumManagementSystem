<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_requests', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('email');
            $table->string('company_name');
            $table->string('name');
            $table->string('phone_number');
            $table->string('address', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_requests');
    }
};