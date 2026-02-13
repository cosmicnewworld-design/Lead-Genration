<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Contracts\CurrentTenant;

/**
 * Trait BelongsToTenant
 * @package App\Traits
 */
trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model) {
            $currentTenant = app(CurrentTenant::class);
            if ($currentTenant->check()) {
                $model->setAttribute('tenant_id', $currentTenant->getId());
            }
        });
    }
}
