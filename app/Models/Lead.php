<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'company_name',
        'scraped_data',
        'status',
        'business_id',
        'follow_up_count',
        'last_follow_up_sent_at',
        'linkedin_profile',
        'email_verified_at',
    ];

    protected $casts = [
        'scraped_data' => 'array',
        'last_follow_up_sent_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
