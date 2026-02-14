<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'tenant_id'];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class);
    }

    public function steps()
    {
        return $this->hasMany(CampaignStep::class)->orderBy('order');
    }

    public function runs()
    {
        return $this->hasMany(CampaignRun::class);
    }
}
