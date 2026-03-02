<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'email',
        'provider',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_active',
        'daily_sent_count',
        'last_reset_date',
        'settings',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'settings' => 'json',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
