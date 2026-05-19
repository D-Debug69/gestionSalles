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
        Schema::table('reservation_salles', function (Blueprint $table) {
            if (!Schema::hasColumn('reservation_salles', 'reservation_date')) {
                $table->date('reservation_date')->nullable()->after('nomSalle');
            }
            if (!Schema::hasColumn('reservation_salles', 'start_time')) {
                $table->time('start_time')->nullable()->after('reservation_date');
            }
            if (!Schema::hasColumn('reservation_salles', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_salles', function (Blueprint $table) {
            if (Schema::hasColumn('reservation_salles', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('reservation_salles', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('reservation_salles', 'reservation_date')) {
                $table->dropColumn('reservation_date');
            }
        });
    }
};
