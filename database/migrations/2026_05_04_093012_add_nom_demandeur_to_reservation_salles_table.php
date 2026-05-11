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
        if (!Schema::hasColumn('reservation_salles', 'nom_demandeur')) {
            Schema::table('reservation_salles', function (Blueprint $table) {
                $table->string('nom_demandeur')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_salles', function (Blueprint $table) {
            $table->dropColumn('nom_demandeur');
        });
    }
};
