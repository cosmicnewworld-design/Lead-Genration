<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasFactory, Billable;

    protected $fillable = ['name', 'domain', 'stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at'];

}
