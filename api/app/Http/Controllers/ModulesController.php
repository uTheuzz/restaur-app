<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modules\StoreModuleRequest;
use App\Models\Module;
use App\Services\ModuleService;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use Spatie\Permission\Models\Permission;

class ModulesController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role_or_permission:SUPER_ADMIN|TENANT_ADMIN']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $modules = Module::paginate(100)->toarray();
            $modulesRoles = [];
            
            foreach ($modules['data'] as $module) {
                $role = PermissionService::getRole($module['code']);
                $module['permissions'] = $role->permissions->pluck('name');
                array_push($modulesRoles, $module);
            }
            
            $modules['data'] = $modulesRoles;

            return response()->json($modules);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModuleRequest $request)
    {
        try {

            return response()->json(ModuleService::store($request->all()));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        try {
            
            $role = PermissionService::getRole($module->code);
            $module['permissions'] = $role->permissions->pluck('name');

            return response()->json($module);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        try {

            return response()->json(ModuleService::update($request->all(), $module));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        try {

            PermissionService::forgetCachedPermissions();

            $role = PermissionService::getRole($module->code);

            $role->delete();

            $module->forceDelete();

            return response()->json($module);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Module $module) {
        try {

            $result = [];
            $module->restore();

            if ($module->deleted_at) {
                $result = $module;
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function createNewPermissions(Request $request, Module $module) {
        try {

            PermissionService::forgetCachedPermissions();

            $data = $request->all();
            $permissionsList = PermissionService::getPermissionsList($data, $module->code);

            $permissions = PermissionService::createPermissions($permissionsList, $module->code);

            if ($permissions) {
                $result = $permissions->getPermissionNames();
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function revokePermissions(Request $request, Module $module) {
        try {

            PermissionService::forgetCachedPermissions();

            $data = $request->all();

            $role = PermissionService::getRole($module->code);

            $role->revokePermissionTo($data['permissions']);

            foreach ($data['permissions'] as $permission) {
                $permission = Permission::where('name', $permission)->first();
                $permission->delete();
            }

            $result = $role->getPermissionNames();

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }  
    }
}
