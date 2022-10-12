<?php

namespace App\Http\Resources;

use App\Models\ModuleTenant;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\Permission\Models\Role;

class TenantResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $list = $this->collection;
        $tenants = [];
        $modules = [];
        $showPaginate = count(explode('/',$request->getRequestUri())) <= 5 ? true : false;

        foreach ($list as $tenant) {
            foreach ($tenant['modules'] as $module) {

                $moduleTenant = ModuleTenant::where([
                    ['module_id', $module['id']], 
                    ['tenant_id', $tenant['id']]
                ])->first();

                $role = Role::where('name', $module['code'])->first();

                $item = [
                    'id' => $module['id'],
                    'name' => $module['name'],
                    'code' => $module['code'],
                    'slug' => $module['slug'],
                    'description' => $module['description'],
                    'start_date' => $moduleTenant['expiration_date'],
                    'expiration_date' => $moduleTenant['expiration_date'],
                    'permissions' => $role->permissions->pluck('name')->toArray()
                ];
                
                array_push($modules, $item);
            }

            $item = [
                'id' => $tenant['id'],
                'name' => $tenant['name'],
                'database_name' => $tenant['database'],
                'database_user' => $tenant['database_user'],
                // 'database_password' => $tenant['database_password'],
                'slug' => $tenant['slug'],
                'code' => $tenant['code'],
                'contact_document_type' => $tenant['contact_document_type'],
                'contact_document' => $tenant['contact_document'],
                'contatc_name' => $tenant['contact_name'],
                'contact_phone' => $tenant['contact_phone'],
                'contact_email' => $tenant['contact_email'],
                'created_at' => $tenant['created_at'],
                'updated_at' => $tenant['updated_at'],
                'deleted_at' => $tenant['deleted_at'],
                'modules' => $modules
            ];
            $modules = [];

            array_push($tenants, $item);
        }

        if ($showPaginate) {
            return new PaginationResource([$this->resource, $tenants]);
        } else {
            return $tenants;
        }

    }
}
