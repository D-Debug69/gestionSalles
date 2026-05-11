<?php

namespace App\Helpers;


use App\Models\Role;

class PermissionHelper
{
    public static function hasPermission($user, $permissionName)
    {
        if (!$user) return false;

        $roles = $user->roles;
        if (is_string($roles)) {
            $roles = json_decode($roles, true) ?: [];
        }

    if (in_array('Admin', $roles, true)) {
        return true;
    }

        $roleNames = array_map('ucfirst', (array) $roles);
        $roles = Role::whereIn('name', $roleNames)->with('permissions')->get();

        foreach ($roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

    return false;
}
}