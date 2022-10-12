<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenantResource;
use Illuminate\Http\Request;
use App\Models\CustomTenant;
use App\Models\Module;
use App\Models\ModuleTenant;
use App\Services\PermissionService;
use Carbon\Carbon;

class TenantsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:SUPER_ADMIN|TENANT_ADMIN'])->except('listCompanyPermissions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $tenants = CustomTenant::paginate(10);

            return response()->json(new TenantResource($tenants));

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
    public function store(Request $request)
    {
       try {
            $data = $request->all();
            $data['code'] = $data['name'];
            $result = [];

            $tenant = CustomTenant::create($data);

            return response()->json($result);

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
    public function show(CustomTenant $tenant)
    {
        try {

            return response()->json(new TenantResource($tenant->get()));

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
    public function update(Request $request, CustomTenant $tenant)
    {
        try {

            $data = $request->all();
            $result = [];

            $tenant->update($data);

            return response()->json($result);

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
    public function destroy($id)
    {
        try {

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restore($id) {
        try {

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignModulesToCompany(Request $request, CustomTenant $tenant) 
    {
        try {

            $data = [];
            $modules = [];
            $excluded = [];
            $included = [];
            $result = [];
            $tenantModules = $tenant->relationsToArray();

            foreach ($tenantModules['modules'] as $module) {
                array_push($modules, $module['id']);
            }

            foreach ($request->all() as $module) {
                if (!in_array($module['module_id'], $modules, true)) {
                    $mod = Module::where('id', $module['module_id'])->pluck('name')->toArray();
                    array_push($included, $mod);
                    $module['tenant_id'] = $tenant->id;
                    $module['created_at'] = Carbon::now();
                    $module['updated_at'] = Carbon::now();
                    array_push($data, $module);
                } else {
                    $mod = Module::where('id', $module['module_id'])->pluck('name');
                    array_push($excluded, $mod);
                }
            }

            ModuleTenant::insert($data);

            if (!empty($excluded)) {
                $result = [
                    'status' => 'success',
                    'message' => "One or more modules were not linked because they are already in the company's register",
                    'excluded' => $excluded,
                    'included' => $included
                ];
            } else {
                $result = [
                    'status' => 'success',
                    'message' => "All modules informed were linked to the company",
                    'included' => $included
                ];
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function revokeModulesToCompany(Request $request, CustomTenant $tenant)
    {
        try {

            $result = [];
            $tenantModules = $tenant->relationsToArray();;
            
            foreach ($tenantModules['modules'] as $module) {
                $moduleTenant = ModuleTenant::where([
                    ['module_id', $module['id']], 
                    ['tenant_id', $tenant['id']]
                ])->first();

                if (in_array($moduleTenant->module_id, $request->all())) {
                    $moduleTenant->delete();
                }
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listCompanyPermissions(CustomTenant $tenant)
    {
        try {
            if (count($tenant->relationsToArray()['modules']) < 1) {
                $tenant = app('currentTenant');
            }
            
            $permissions = PermissionService::getCompanyPermissionsList($tenant);

            return response()->json($permissions);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
