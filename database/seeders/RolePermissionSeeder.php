<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $roles = [
            'Admin',
            'Dg',
            'Dfc',
            'Cc',
            'Rgs',
            'User',
        ];

        $permissions = [
            'create reservation',
            'view reservation',
            'update reservation',
            'delete reservation',
            'accept reservation',
            'refuse reservation',
            'create salle',
            'view salle',
            'update salle',
            'delete salle',
            'create user',
            'view user',
            'update user',
            'delete user',
            'create pays',
            'delete pays',
            'create ville',
            'view ville',
            'update ville',
            'delete ville',
            'create chreno',
            'update chreno',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $admin = Role::where('name', 'Admin')->first();

        if ($admin) {
            $admin->permissions()->sync(Permission::pluck('id')->toArray());
        }
    }
}