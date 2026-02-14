<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\LeadScoringService;
use App\Services\LeadSegmentationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateLeadScores implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(LeadScoringService $scoringService, LeadSegmentationService $segmentationService): void
    {
        Lead::where('tenant_id', $this->tenantId)->each(function (Lead $lead) use ($scoringService, $segmentationService) {
            $scoringService->updateLeadScore($lead);
            $segmentationService->segmentLead($lead);
        });
    }
}
