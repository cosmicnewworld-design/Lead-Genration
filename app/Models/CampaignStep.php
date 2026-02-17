<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'campaign_id',
        'type',
        'subject',
        'body',
        'delay_in_days',
        'delay_in_hours',
        'delay_in_minutes',
        'order',
        'settings',
        'ab_test_variant',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getDelayInMinutes(): int
    {
        $minutes = 0;
        $minutes += ($this->delay_in_days ?? 0) * 24 * 60;
        $minutes += ($this->delay_in_hours ?? 0) * 60;
        $minutes += ($this->delay_in_minutes ?? 0);
        return $minutes;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
