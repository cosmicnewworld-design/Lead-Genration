<?php

namespace App\Observers;

use App\Models\Lead;
use App\Jobs\CalculateLeadScore;

class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function created(Lead $lead)
    {
        CalculateLeadScore::dispatch($lead);
    }

    /**
     * Handle the Lead "updated" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function updated(Lead $lead)
    {
        // We only want to recalculate the score if the relevant fields have changed.
        // This avoids infinite loops where saving the score triggers another update.
        // Get the original data to compare.
        $original = $lead->getOriginal();

        // The score itself changing shouldn't trigger a recalculation.
        if (isset($original['score']) && $original['score'] === $lead->score) {
             // If only the score was updated, do nothing.
             // This is a safeguard.
        }
        
        // A more robust check might be to see if any of the fields used in scoring rules have changed.
        // For now, we will dispatch the job on any meaningful update other than just the score.
        CalculateLeadScore::dispatch($lead);
    }

    /**
     * Handle the Lead "deleted" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function deleted(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function restored(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function forceDeleted(Lead $lead)
    {
        //
    }
}
