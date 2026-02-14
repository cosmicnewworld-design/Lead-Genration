<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['name', 'email', 'phone', 'source'];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function campaignRuns()
    {
        return $this->belongsToMany(CampaignRun::class, 'campaign_run_leads')->withTimestamps();
    }
}
