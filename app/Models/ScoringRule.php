<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'condition_field',
        'operator',
        'condition_value',
        'points',
        'tenant_id',
    ];
}
