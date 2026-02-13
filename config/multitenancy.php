<?php

use App\TenantFinder\DomainTenantFinder;
use Spatie\Multitenancy\Actions\ForgetCurrentTenantAction;
use Spatie\Multitenancy\Actions\MakeQueueableTenantAwareAction;
use Spatie\Multitenancy\Actions\MakeTenantCurrentAction;
use Spatie\Multitenancy\Actions\MigrateTenantAction;

return [
    'tenant_finder' => DomainTenantFinder::class,

    'tenant_artisan_search_fields' => [
        'id',
    ],

    'switch_tenant_tasks' => [
        // Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class, // Disabled for single DB tenancy
    ],

    'tenant_model' => \App\Models\Tenant::class,

    'queues_are_tenant_aware_by_default' => true,

    'landlord_database_connection_name' => null, // Use default connection

    'tenant_database_connection_name' => null, // Use default connection

    'apply_tenant_scope_on_fremework_classes' => false,

    'current_tenant_container_key' => 'currentTenant',

    'forget_current_tenant_action' => ForgetCurrentTenantAction::class,

    'make_tenant_current_action' => MakeTenantCurrentAction::class,

    'make_queueable_tenant_aware_action' => MakeQueueableTenantAwareAction::class,

    'migrate_tenant_action' => MigrateTenantAction::class,

    'tenant_id_column_name' => 'tenant_id',

    'tenant_aware_models' => [
        App\Models\User::class,
        App\Models\Lead::class,
    ],
];
