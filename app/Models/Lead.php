<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'tenant_id',
        'source',
        'score', // Make score fillable
    ];

    /**
     * Get the tenant that owns the lead.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the activities for the lead.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the attachments for the lead.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the source of the lead.
     */
    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }
}
