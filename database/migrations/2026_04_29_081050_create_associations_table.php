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
        Schema::create('associations', function (Blueprint $table) {
    $table->id();
    $table->string('nomAssociation');
    $table->string('typeAssociation')->nullable();
    $table->date('dateCreationA')->nullable();
    $table->string('adresseCompleteA')->nullable();
    $table->string('pays')->nullable();
    $table->string('ville')->nullable();
    $table->string('telephoneA')->nullable();
    $table->string('adressePostaleA')->nullable();
    $table->string('email')->nullable();
    $table->string('recepisse')->nullable();
    $table->string('autorisationMairieA')->nullable();
    $table->string('documentForceA')->nullable();
    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associations');
    }
};
