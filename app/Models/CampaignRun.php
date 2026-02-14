<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignRun extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'campaign_id',
        'started_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'campaign_run_leads')->withTimestamps();
    }
}
