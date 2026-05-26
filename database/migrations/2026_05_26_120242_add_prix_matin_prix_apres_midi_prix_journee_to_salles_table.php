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
        Schema::table('salles', function (Blueprint $table) {
            $table->decimal('prix_matin', 10, 2)->nullable()->after('prix');
    $table->decimal('prix_apres_midi', 10, 2)->nullable()->after('prix_matin');
    $table->decimal('prix_journee', 10, 2)->nullable()->after('prix_apres_midi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->dropColumn('prix_matin');
            $table->dropColumn('prix_apres_midi');
            $table->dropColumn('prix_journee');
        });
    }
};
