<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model) {
            if (Tenant::current()) {
                $model->setAttribute('tenant_id', Tenant::current()->getKey());
            }
        });
    }
}
