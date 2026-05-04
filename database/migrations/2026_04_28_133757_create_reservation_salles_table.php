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
        Schema::create('reservation_salles', function (Blueprint $table) {
        $table->id();
        $table->string('statut')->default('pending');
         $table->foreignId('user_id')->nullable()->onDelete('cascade');
        $table->string('nomSalle')->nullable();
        $table->timestamp('dateInscription')->nullable();
        $table->timestamp('dateEmission')->nullable();
        $table->timestamp('dateTraitement')->nullable();
        $table->string('motifRejet')->nullable();
        $table->string('pdf_path')->nullable(); // pour stocker PDF
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_salles');
    }
};
