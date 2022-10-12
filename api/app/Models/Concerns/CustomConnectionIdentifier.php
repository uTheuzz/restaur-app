<?php 

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

trait CustomConnectionIdentifier {
    
    use UsesMultitenancyConfig;

    public function getConnectionName()
    {
        if (Config::get('ConnectionName') == 'landlord') {
            return $this->landlordDatabaseConnectionName();
        } else if (Config::get('ConnectionName') == 'tenant'){
            return $this->tenantDatabaseConnectionName();
        } else {
            return $this->landlordDatabaseConnectionName();
        }
    }
}