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
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('image')->nullable();
            $table->integer('capacite')->nullable();
            $table->string('equipements')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->foreignId('ville_id')->constrained('villes')->onDelete('cascade');
            $table->enum('statut', ['disponible', 'indisponible'])->default('indisponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
