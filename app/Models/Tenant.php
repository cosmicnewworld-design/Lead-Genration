<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasFactory, Billable, UsesTenantConnection;

    protected $fillable = ['name', 'database'];

    protected $casts = [
        'database' => 'array',
    ];

    public function getDatabaseName(): string
    {
        return $this->database['name'];
    }
}
