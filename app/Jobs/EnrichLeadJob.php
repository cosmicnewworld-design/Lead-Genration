<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\ScraperService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnrichLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Lead $lead;

    /**
     * Create a new job instance.
     *
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * This job enriches a lead with additional information, such as their LinkedIn profile, email address, and social media links.
     *
     * @param ScraperService $scraperService
     * @return void
     */
    public function handle(ScraperService $scraperService): void
    {
        // Find the lead's LinkedIn profile.
        $linkedinProfile = $scraperService->findLinkedInProfile($this->lead->name, $this->lead->business->name);
        if ($linkedinProfile) {
            $this->lead->linkedin_url = $linkedinProfile;
        }

        // Find and verify the lead's email address.
        $email = $scraperService->findEmail($this->lead->name, parse_url($this->lead->business->website, PHP_URL_HOST));
        if ($email && $scraperService->verifyEmail($email)) {
            $this->lead->email = $email;
        }

        // Find the lead's social media links.
        $socials = $scraperService->findSocials($this->lead->business->website);
        if($socials) {
            $this->lead->socials = $socials;
        }

        $this->lead->save();
    }
}
