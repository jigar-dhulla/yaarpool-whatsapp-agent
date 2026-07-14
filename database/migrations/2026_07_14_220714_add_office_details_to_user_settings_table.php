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
        Schema::table('user_settings', function (Blueprint $table) {
            $table->time('office_start_time')->nullable()->after('default_to_location');
            $table->time('office_end_time')->nullable()->after('office_start_time');
            $table->json('office_days')->nullable()->after('office_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn(['office_start_time', 'office_end_time', 'office_days']);
        });
    }
};
