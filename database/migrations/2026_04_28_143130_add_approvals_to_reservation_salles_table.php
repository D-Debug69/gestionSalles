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
    $table->string('nom_demandeur')->nullable()->after('motifRejet');
    $table->string('telephone')->nullable()->after('nom_demandeur');
    $table->string('email')->nullable()->after('telephone');
    $table->text('details')->nullable()->after('email');

    $table->boolean('approved_cc')->default(false);
    $table->foreignId('approved_cc_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_cc_at')->nullable();

    $table->boolean('approved_dfc')->default(false);
    $table->foreignId('approved_dfc_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_dfc_at')->nullable();

    $table->boolean('approved_dg')->default(false);
    $table->foreignId('approved_dg_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_dg_at')->nullable();

    $table->boolean('approved_admin')->default(false);
    $table->foreignId('approved_admin_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('approved_admin_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_salles', function (Blueprint $table) {
            $table->dropColumn(['nom_demandeur', 'telephone', 'email', 'details']);
            $table->dropColumn(['approved_cc', 'approved_cc_by', 'approved_cc_at']);
            $table->dropColumn(['approved_dfc', 'approved_dfc_by', 'approved_dfc_at']);
            $table->dropColumn(['approved_dg', 'approved_dg_by', 'approved_dg_at']);
            $table->dropColumn(['approved_admin', 'approved_admin_by', 'approved_admin_at']);
        });
    }
};
