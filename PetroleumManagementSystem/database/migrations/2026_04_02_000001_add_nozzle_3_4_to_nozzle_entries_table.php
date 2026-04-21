<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nozzle_entries', function (Blueprint $table) {
            $table->decimal('diesel_n3_opening', 10, 2)->default(0)->after('diesel_n2_closing');
            $table->decimal('diesel_n3_closing', 10, 2)->default(0)->after('diesel_n3_opening');
            $table->decimal('diesel_n4_opening', 10, 2)->default(0)->after('diesel_n3_closing');
            $table->decimal('diesel_n4_closing', 10, 2)->default(0)->after('diesel_n4_opening');

            $table->decimal('petrol_n3_opening', 10, 2)->default(0)->after('petrol_n2_closing');
            $table->decimal('petrol_n3_closing', 10, 2)->default(0)->after('petrol_n3_opening');
            $table->decimal('petrol_n4_opening', 10, 2)->default(0)->after('petrol_n3_closing');
            $table->decimal('petrol_n4_closing', 10, 2)->default(0)->after('petrol_n4_opening');
        });
    }

    public function down(): void
    {
        Schema::table('nozzle_entries', function (Blueprint $table) {
            $table->dropColumn([
                'diesel_n3_opening',
                'diesel_n3_closing',
                'diesel_n4_opening',
                'diesel_n4_closing',
                'petrol_n3_opening',
                'petrol_n3_closing',
                'petrol_n4_opening',
                'petrol_n4_closing',
            ]);
        });
    }
};
