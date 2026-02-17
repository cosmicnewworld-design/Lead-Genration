<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tenant_id',
        'status',
        'type',
        'schedule_settings',
        'ab_test_enabled',
        'sender_email_id',
        'reply_to_email',
        'track_opens',
        'track_clicks',
        'auto_follow_up',
        'stop_on_reply',
    ];

    protected $casts = [
        'schedule_settings' => 'array',
        'ab_test_enabled' => 'boolean',
        'track_opens' => 'boolean',
        'track_clicks' => 'boolean',
        'auto_follow_up' => 'boolean',
        'stop_on_reply' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class)->withPivot(['status', 'current_step_id', 'processed_at', 'opened_at', 'clicked_at', 'replied_at'])->withTimestamps();
    }

    public function steps(): HasMany
    {
        return $this->hasMany(CampaignStep::class)->orderBy('order');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(CampaignRun::class);
    }

    public function senderEmail(): BelongsTo
    {
        return $this->belongsTo(ConnectedEmail::class, 'sender_email_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getStatsAttribute(): array
    {
        $totalLeads = $this->leads()->count();
        $sentLeads = $this->leads()->wherePivot('status', 'sent')->count();
        $openedLeads = $this->leads()->wherePivotNotNull('opened_at')->count();
        $clickedLeads = $this->leads()->wherePivotNotNull('clicked_at')->count();
        $repliedLeads = $this->leads()->wherePivotNotNull('replied_at')->count();

        return [
            'total_leads' => $totalLeads,
            'sent' => $sentLeads,
            'opened' => $openedLeads,
            'clicked' => $clickedLeads,
            'replied' => $repliedLeads,
            'open_rate' => $sentLeads > 0 ? round(($openedLeads / $sentLeads) * 100, 2) : 0,
            'click_rate' => $sentLeads > 0 ? round(($clickedLeads / $sentLeads) * 100, 2) : 0,
            'reply_rate' => $sentLeads > 0 ? round(($repliedLeads / $sentLeads) * 100, 2) : 0,
        ];
    }
}
