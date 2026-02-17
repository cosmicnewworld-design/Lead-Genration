<?php

namespace App\Services;

use App\Models\ConnectedEmail;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EmailIntegrationService
{
    /**
     * Connect Gmail account via OAuth
     */
    public function connectGmail(Tenant $tenant, User $user, string $code): ConnectedEmail
    {
        try {
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'grant_type' => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                throw new \Exception('Failed to get access token: ' . $tokenResponse->body());
            }

            $tokens = $tokenResponse->json();
            $userInfo = $this->getGmailUserInfo($tokens['access_token']);

            return ConnectedEmail::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'email' => $userInfo['email'],
                'provider' => 'gmail',
                'access_token' => encrypt($tokens['access_token']),
                'refresh_token' => encrypt($tokens['refresh_token'] ?? null),
                'token_expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
                'is_active' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Gmail connection error', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }

    /**
     * Connect Outlook account via OAuth
     */
    public function connectOutlook(Tenant $tenant, User $user, string $code): ConnectedEmail
    {
        try {
            $tokenResponse = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
                'code' => $code,
                'client_id' => config('services.microsoft.client_id'),
                'client_secret' => config('services.microsoft.client_secret'),
                'redirect_uri' => config('services.microsoft.redirect'),
                'grant_type' => 'authorization_code',
                'scope' => 'https://graph.microsoft.com/Mail.Send offline_access',
            ]);

            if (!$tokenResponse->successful()) {
                throw new \Exception('Failed to get access token: ' . $tokenResponse->body());
            }

            $tokens = $tokenResponse->json();
            $userInfo = $this->getOutlookUserInfo($tokens['access_token']);

            return ConnectedEmail::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'email' => $userInfo['mail'] ?? $userInfo['userPrincipalName'],
                'provider' => 'outlook',
                'access_token' => encrypt($tokens['access_token']),
                'refresh_token' => encrypt($tokens['refresh_token'] ?? null),
                'token_expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
                'is_active' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Outlook connection error', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }

    /**
     * Refresh access token for Gmail
     */
    public function refreshGmailToken(ConnectedEmail $connectedEmail): bool
    {
        try {
            $refreshToken = decrypt($connectedEmail->refresh_token);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'grant_type' => 'refresh_token',
            ]);

            if (!$response->successful()) {
                return false;
            }

            $tokens = $response->json();
            $connectedEmail->update([
                'access_token' => encrypt($tokens['access_token']),
                'token_expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Gmail token refresh error', [
                'error' => $e->getMessage(),
                'connected_email_id' => $connectedEmail->id,
            ]);
            return false;
        }
    }

    /**
     * Refresh access token for Outlook
     */
    public function refreshOutlookToken(ConnectedEmail $connectedEmail): bool
    {
        try {
            $refreshToken = decrypt($connectedEmail->refresh_token);

            $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.microsoft.client_id'),
                'client_secret' => config('services.microsoft.client_secret'),
                'grant_type' => 'refresh_token',
                'scope' => 'https://graph.microsoft.com/Mail.Send offline_access',
            ]);

            if (!$response->successful()) {
                return false;
            }

            $tokens = $response->json();
            $connectedEmail->update([
                'access_token' => encrypt($tokens['access_token']),
                'token_expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Outlook token refresh error', [
                'error' => $e->getMessage(),
                'connected_email_id' => $connectedEmail->id,
            ]);
            return false;
        }
    }

    /**
     * Send email via Gmail API
     */
    public function sendViaGmail(ConnectedEmail $connectedEmail, string $to, string $subject, string $body, ?string $replyTo = null): bool
    {
        try {
            if ($connectedEmail->isTokenExpired()) {
                if (!$this->refreshGmailToken($connectedEmail)) {
                    throw new \Exception('Failed to refresh Gmail token');
                }
            }

            $accessToken = decrypt($connectedEmail->access_token);
            $message = $this->createGmailMessage($to, $subject, $body, $replyTo);

            $response = Http::withToken($accessToken)
                ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                    'raw' => base64_encode($message),
                ]);

            if ($response->successful()) {
                $connectedEmail->increment('daily_sent_count');
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Gmail send error', [
                'error' => $e->getMessage(),
                'connected_email_id' => $connectedEmail->id,
            ]);
            return false;
        }
    }

    /**
     * Send email via Outlook API
     */
    public function sendViaOutlook(ConnectedEmail $connectedEmail, string $to, string $subject, string $body, ?string $replyTo = null): bool
    {
        try {
            if ($connectedEmail->isTokenExpired()) {
                if (!$this->refreshOutlookToken($connectedEmail)) {
                    throw new \Exception('Failed to refresh Outlook token');
                }
            }

            $accessToken = decrypt($connectedEmail->access_token);

            $response = Http::withToken($accessToken)
                ->post('https://graph.microsoft.com/v1.0/me/sendMail', [
                    'message' => [
                        'subject' => $subject,
                        'body' => [
                            'contentType' => 'HTML',
                            'content' => $body,
                        ],
                        'toRecipients' => [
                            ['emailAddress' => ['address' => $to]],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $connectedEmail->increment('daily_sent_count');
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Outlook send error', [
                'error' => $e->getMessage(),
                'connected_email_id' => $connectedEmail->id,
            ]);
            return false;
        }
    }

    /**
     * Get Gmail user info
     */
    private function getGmailUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        return $response->json();
    }

    /**
     * Get Outlook user info
     */
    private function getOutlookUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get('https://graph.microsoft.com/v1.0/me');

        return $response->json();
    }

    /**
     * Create Gmail message format
     */
    private function createGmailMessage(string $to, string $subject, string $body, ?string $replyTo = null): string
    {
        $message = "To: {$to}\r\n";
        $message .= "Subject: {$subject}\r\n";
        if ($replyTo) {
            $message .= "Reply-To: {$replyTo}\r\n";
        }
        $message .= "Content-Type: text/html; charset=utf-8\r\n";
        $message .= "\r\n{$body}";

        return $message;
    }

    /**
     * Check if email can send (rate limiting)
     */
    public function canSend(ConnectedEmail $connectedEmail, int $maxPerDay = 100): bool
    {
        // Reset daily count if needed
        if ($connectedEmail->last_reset_date !== today()) {
            $connectedEmail->resetDailyCount();
        }

        return $connectedEmail->daily_sent_count < $maxPerDay;
    }
}
