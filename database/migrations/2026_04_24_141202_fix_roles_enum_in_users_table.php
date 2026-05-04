<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // D'abord, changer les rôles existants problématiques
        DB::table('users')->where('role', '')->update(['role' => 'user']);
        DB::table('users')->where('role', 'DG')->update(['role' => 'dg']);
        DB::table('users')->where('role', 'DFC')->update(['role' => 'dfc']);
        DB::table('users')->where('role', 'CC')->update(['role' => 'cc']);
        
        // Puis modifier l'enum
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dfc', 'rgs', 'dg', 'cc', 'user'])->default('user')->change();
        });
    }

    public function down(): void
    {
        // Remettre l'ancien enum
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', '', 'user', 'DG', 'DFC', 'CC'])->default('user')->change();
        });
        
        // Remettre les anciennes valeurs
        DB::table('users')->where('role', 'dg')->update(['role' => 'DG']);
        DB::table('users')->where('role', 'dfc')->update(['role' => 'DFC']);
        DB::table('users')->where('role', 'cc')->update(['role' => 'CC']);
    }
};