<?php

namespace App\Observers;

use App\Models\ScoringRule;
use App\Jobs\RecalculateLeadScores;

class ScoringRuleObserver
{
    /**
     * Handle the ScoringRule "created" event.
     */
    public function created(ScoringRule $scoringRule): void
    {
        RecalculateLeadScores::dispatch($scoringRule->tenant_id);
    }

    /**
     * Handle the ScoringRule "updated" event.
     */
    public function updated(ScoringRule $scoringRule): void
    {
        RecalculateLeadScores::dispatch($scoringRule->tenant_id);
    }

    /**
     * Handle the ScoringRule "deleted" event.
     */
    public function deleted(ScoringRule $scoringRule): void
    {
        RecalculateLeadScores::dispatch($scoringRule->tenant_id);
    }
}
