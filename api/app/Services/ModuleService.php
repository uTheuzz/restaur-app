<?php

namespace App\Services;

use App\Models\Module;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\PermissionService;

class ModuleService
{
    public static function store(array $data)
    {
        $data['code'] = $data['name'];
        $module = Module::create($data);
        $result = [];
        
        if ($module) {
            $role = Role::create(['name' => $module->code]);

            if ($role) {
                $permissionsList = PermissionService::getPermissionsList($data, $module->code);
                PermissionService::createPermissions($permissionsList, $module->code);
            }

            $result = $module;
        }

        return $result;
    }

    public static function update(array $data, Module $module)
    {
        $result = [];

        if (!empty($data)) {
            $module->update($data);
            $role = self::getRole($module->code);
            $module['permissions'] = $role->permissions->pluck('name');
            $result = $module;
        }

        return $result;
    }

    public static function createPermissions(array $list, string $roleName)
    {
        $role = PermissionService::getRole($roleName); 

        foreach ($list as $permission) {
            $permission = strtoupper($permission);
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
}