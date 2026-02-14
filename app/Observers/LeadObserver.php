<?php

namespace App\Observers;

use App\Models\Lead;
use App\Services\LeadScoringService;

class LeadObserver
{
    protected $scoringService;

    public function __construct(LeadScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {
        $this->scoringService->updateLeadScore($lead);
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        $this->scoringService->updateLeadScore($lead);
    }

    /**
     * Handle the Lead "deleted" event.
     */
    public function deleted(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     */
    public function restored(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     */
    public function forceDeleted(Lead $lead): void
    {
        //
    }
}
