<?php

namespace App\CustomTenantFinder;

use App\Models\CustomTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CustomTenantFinder extends TenantFinder 
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        $data = $request->all();

        if ((mb_strpos($request->getRequestUri(), 'login') !== false) || (mb_strpos($request->getRequestUri(), 'register') !== false)) {
            $company_id = array_key_exists('company_id', $data) ? $data['company_id'] : null;
        } else {
            $company_id = Cache::get("company_id");
        }

        $tenant = null;

        if (!$company_id) {
            Config::set('ConnectionName', 'landlord');
        } else {
            $tenant = CustomTenant::where('id', $company_id)->first();
            Config::set('ConnectionName', 'tenant');
        }

        return $tenant;
    }

}