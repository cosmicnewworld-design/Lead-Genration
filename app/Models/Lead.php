<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\IsScopedByTenant;

class Lead extends Model
{
    use HasFactory, IsScopedByTenant;

    protected $fillable = ['name', 'email', 'phone', 'campaign_id'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignRuns()
    {
        return $this->belongsToMany(CampaignRun::class, 'campaign_run_leads')->withTimestamps();
    }
}
