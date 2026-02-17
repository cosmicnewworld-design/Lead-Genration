<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Contracts\CurrentTenant;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'webhook_secret',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            if (!$tenant->slug) {
                $tenant->slug = Str::slug($tenant->name);
            }
            if (!$tenant->webhook_secret) {
                $tenant->webhook_secret = Str::random(32);
            }
        });
    }

    public static function current(): ?self
    {
        // Prefer Spatie multitenancy if configured
        if (app()->bound(CurrentTenant::class)) {
            $currentTenant = app(CurrentTenant::class);
            if (method_exists($currentTenant, 'check') && $currentTenant->check()) {
                // Some implementations expose getId(); we can fetch the model via ID.
                $tenantId = $currentTenant->getId();
                if ($tenantId) {
                    return self::find($tenantId);
                }
            }
        }

        // Fallback to the app container binding used by IdentifyTenant middleware
        if (app()->bound('tenant')) {
            $t = app('tenant');
            return $t instanceof self ? $t : null;
        }

        return null;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function business()
    {
        return $this->hasOne(Business::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function leadSourceCategories()
    {
        return $this->hasMany(LeadSourceCategory::class);
    }

    public function leadSources()
    {
        return $this->hasMany(LeadSource::class);
    }
}
