<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'last_reset_date' => 'date',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    public function resetDailyCount(): void
    {
        $this->daily_sent_count = 0;
        $this->last_reset_date = now();
        $this->save();
    }
}
