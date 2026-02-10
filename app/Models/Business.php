<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'address',
        'phone',
        'google_maps_url',
        'description',
        'whatsapp_number',
        'contact_email',
        'tenant_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
