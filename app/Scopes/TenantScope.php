<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Contracts\CurrentTenant;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $currentTenant = app(CurrentTenant::class);

        if ($currentTenant->check()) {
            $builder->where($model->qualifyColumn('tenant_id'), $currentTenant->getId());
        }
    }
}
