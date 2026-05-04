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
Schema::create('entreprises', function (Blueprint $table) {
    $table->id();
    $table->string('nomEntreprise');
    $table->string('typeEntreprise')->nullable();
    $table->date('dateCreationE')->nullable();
    $table->string('adresseCompleteE')->nullable();
    $table->string('pays')->nullable();
    $table->string('ville')->nullable();
    $table->string('telephoneE')->nullable();
    $table->string('adressePostaleE')->nullable();
    $table->string('rccm')->nullable();
    $table->string('ifu')->nullable();
    $table->string('autorisationMairieE')->nullable();
    $table->string('documentForceE')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
