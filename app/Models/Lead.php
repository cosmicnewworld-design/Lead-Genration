<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'job_title',
        'website',
        'notes',
        'status',
        'score',
        'pipeline_stage_id',
        'assigned_to_user_id',
        'custom_fields',
        'enrichment_data',
        'last_contacted_at',
        'source',
        'lead_source_id',
        'is_qualified',
        'tenant_id',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'enrichment_data' => 'array',
        'last_contacted_at' => 'datetime',
        'is_qualified' => 'boolean',
        'score' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pipelineStage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function scopeQualified($query)
    {
        return $query->where('is_qualified', true);
    }

    public function scopeHighScore($query, $minScore = 50)
    {
        return $query->where('score', '>=', $minScore);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
