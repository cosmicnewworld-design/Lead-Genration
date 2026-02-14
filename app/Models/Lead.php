<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone_number', 'linkedin_url', 'socials', 'status', 'business_id', 'tenant_id'
    ];

    protected $casts = [
        'socials' => 'json',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
