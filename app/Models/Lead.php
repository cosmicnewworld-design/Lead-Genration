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
        'email',
        'phone',
        'status',
        'scraped_data',
        'business_id',
        'linkedin_profile',
    ];

    protected $casts = [
        'scraped_data' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
