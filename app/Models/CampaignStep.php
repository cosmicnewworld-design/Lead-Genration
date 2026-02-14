<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignStep extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'campaign_id',
        'subject',
        'body',
        'delay_in_days',
        'order',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
