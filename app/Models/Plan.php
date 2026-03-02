<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'stripe_plan', // Add this line
        'stripe_plan_id',
        'stripe_price_id',
        'price',
        'billing_period',
        'description',
        'max_leads',
        'max_campaigns',
        'max_team_members',
        'max_emails_per_month',
        'email_warmup',
        'lead_enrichment',
        'api_access',
        'webhook_access',
        'advanced_analytics',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_leads' => 'integer',
        'max_campaigns' => 'integer',
        'max_team_members' => 'integer',
        'max_emails_per_month' => 'integer',
        'email_warmup' => 'boolean',
        'lead_enrichment' => 'boolean',
        'api_access' => 'boolean',
        'webhook_access' => 'boolean',
        'advanced_analytics' => 'boolean',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isUnlimited($feature): bool
    {
        $limit = match($feature) {
            'leads' => $this->max_leads,
            'campaigns' => $this->max_campaigns,
            'team_members' => $this->max_team_members,
            'emails' => $this->max_emails_per_month,
            default => null,
        };

        return $limit === 0;
    }

    public function hasFeature($feature): bool
    {
        return match($feature) {
            'email_warmup' => $this->email_warmup,
            'lead_enrichment' => $this->lead_enrichment,
            'api_access' => $this->api_access,
            'webhook_access' => $this->webhook_access,
            'advanced_analytics' => $this->advanced_analytics,
            default => false,
        };
    }
}
