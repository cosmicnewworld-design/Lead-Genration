<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\ScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyLeadEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lead;

    /**
     * Create a new job instance.
     *
     * @param Lead $lead
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @param ScraperService $scraperService
     * @return void
     */
    public function handle(ScraperService $scraperService): void
    {
        if (empty($this->lead->email)) {
            return;
        }

        try {
            Log::info("Verifying email for lead: {$this->lead->id}");
            $isEmailValid = $scraperService->verifyEmail($this->lead->email);

            if ($isEmailValid) {
                $this->lead->update(['email_verified_at' => now()]);
                Log::info("Email verified for lead: {$this->lead->id}");
            }
        } catch (\Exception $e) {
            Log::error("Email verification failed for lead: {$this->lead->id}. Error: " . $e->getMessage());
        }
    }
}
