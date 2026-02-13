<?php

use App\TenantFinder\DomainTenantFinder;
use Spatie\Multitenancy\Actions\ForgetCurrentTenantAction;
use Spatie\Multitenancy\Actions\MakeQueueableTenantAwareAction;
use Spatie\Multitenancy\Actions\MakeTenantCurrentAction;
use Spatie\Multitenancy\Actions\MigrateTenantAction;
use Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask;

return [
    /*
     * This class is responsible for determining which tenant should be current
     * for the given request.
     *
     * This class should extend `Spatie\Multitenancy\TenantFinder\TenantFinder`
     *
     * You can change this to a class of your own that extends the one above.
     *
     * In the default implementation, we'll look for a `tenant` parameter in the route
     * and use that to decide which tenant is current.
     */
    'tenant_finder' => DomainTenantFinder::class,

    /*
     * These fields are used by the tenancy setup command to determine
     * which fields should be used to identify the tenant.
     */
    'tenant_artisan_search_fields' => [
        'id',
    ],

    /*
     * These tasks will be performed when switching tenants.
     *
     * A valid task is any class that implements `Spatie\Multitenancy\Tasks\SwitchTenantTask`
     */
    'switch_tenant_tasks' => [
        SwitchTenantDatabaseTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must be a class that implements `Spatie\Multitenancy\Contracts\IsTenant`
     *
     * You can change this to a class of your own Tenant model.
     */
    'tenant_model' => \App\Models\Tenant::class,

    /*
     * If there is a current tenant when a job is dispatched, the id of the current tenant
     * will be automatically set on the job. When the job is executed, the set
     * tenant on the job will be made current.
     */
    'queues_are_tenant_aware_by_default' => true,

    /*
     * The connection name to reach the landlord database.
     *
     * Set to `null` to use the default connection.
     */
    'landlord_database_connection_name' => 'landlord',

    /*
     * The connection name to reach the tenant database.
     *
     * Set to `null` to use the default connection.
     */
    'tenant_database_connection_name' => 'tenant',

    /*
     * Set this to `true` if you like to apply the tenant scope on framework classes.
     *
     * For example, if you want to scope the cache, you can set this to `true`.
     * Be aware that this might break certain framework functionalities.
     */
    'apply_tenant_scope_on_fremework_classes' => false,

    /*
     * This is the key that will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * Set this to a class that implements `Spatie\Multitenancy\Actions\ForgetCurrentTenantAction`
     * to customize the logic that is executed when forgetting a tenant.
     */
    'forget_current_tenant_action' => ForgetCurrentTenantAction::class,

    /*
     * Set this to a class that implements `Spatie\Multitenancy\Actions\MakeTenantCurrentAction`
     * to customize the logic that is executed when making a tenant current.
     */
    'make_tenant_current_action' => MakeTenantCurrentAction::class,

    /*
     * Set this to a class that implements `Spatie\Multitenancy\Actions\MakeQueueableTenantAwareAction`
     * to customize the logic that is executed when making a queueable tenant aware.
     */
    'make_queueable_tenant_aware_action' => MakeQueueableTenantAwareAction::class,

    /*
     * Set this to a class that implements `Spatie\Multitenancy\Actions\MigrateTenantAction`
     * to customize the logic that is executed when migrating a tenant.
     */
    'migrate_tenant_action' => MigrateTenantAction::class,

    /*
     * The name of the column that will be used to store the tenant id.
     *
     * This is used by the `IsScopedByTenant` trait.
     */
    'tenant_id_column_name' => 'tenant_id',

    /*
     * This array contains the names of all models that are tenant-aware.
     *
     * A tenant-aware model is a model that has a `tenant_id` column.
     */
    'tenant_aware_models' => [
        App\Models\User::class,
        App\Models\Lead::class,
    ],
];
