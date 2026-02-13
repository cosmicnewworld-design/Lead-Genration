<?php

namespace App\TenantFinder;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\CurrentTenant;
use Spatie\Multitenancy\Contracts\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        return app(CurrentTenant::class)->getTenantModel()::where('domain', $host)->first();
    }
}
