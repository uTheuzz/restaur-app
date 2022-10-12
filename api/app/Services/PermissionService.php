<?php

namespace App\Services;

use App\Models\CustomTenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public static function createPermissions(array $list, string $roleName)
    {
        $role = self::getRole($roleName); 

        foreach ($list as $permission) {
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }

        return $role;
    }

    public static function getRole(string $roleName)
    {
        $role = Role::where('name', strtoupper($roleName))->first();

        return $role;
    }

    public static function getPermissionsList(array $data, string $roleName = null)
    {
        $permissionsList = [];

        foreach ($data['permissions'] as $permission) {
            $perm = strtoupper($roleName).'_'.$permission;
            array_push($permissionsList, $perm);
        }

        return $permissionsList;
    }

    public static function getCompanyPermissionsList(CustomTenant $tenant)
    {
        $permissions = [];

        foreach ($tenant->relationsToArray()['modules'] as $module) {
            $role = self::getRole($module['code']);
            $perms = $role->permissions->pluck('name');
            foreach ($perms as $permission) {
                array_push($permissions, $permission);
            }
        }

        return $permissions;
    }

    public static function forgetCachedPermissions()
    {
        return app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}