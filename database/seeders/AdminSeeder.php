<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'prenom' => 'Admin',
                'telephone' => '22',
                'ville' => 'Tunis',
                'email' => 'admin@test.com',
                'password' => bcrypt('1234'),
                'roles' => ['Admin'],
            ]
        );
    }
}
