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

class FindLeadLinkedInProfileJob implements ShouldQueue
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
        if (empty($this->lead->name) || empty($this->lead->company_name)) {
            return;
        }

        try {
            Log::info("Finding LinkedIn profile for lead: {$this->lead->id}");
            $profileUrl = $scraperService->findLinkedInProfile($this->lead->name, $this->lead->company_name);

            if ($profileUrl) {
                $this->lead->update(['linkedin_profile' => $profileUrl]);
                Log::info("LinkedIn profile found for lead: {$this->lead->id}");
            }
        } catch (\Exception $e) {
            Log::error("LinkedIn profile lookup failed for lead: {$this->lead->id}. Error: " . $e->getMessage());
        }
    }
}
