<?php

namespace App\Services;

use App\Models\Lead;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Jobs\VerifyLeadEmailJob;
use App\Jobs\FindLeadLinkedInProfileJob;

class LeadService
{
    /**
     * Create a new lead and dispatch background jobs.
     *
     * @param StoreLeadRequest $request
     * @return Lead
     */
    public function createLead(StoreLeadRequest $request): Lead
    {
        $lead = Lead::create($request->validated());

        // Dispatch jobs for background processing
        VerifyLeadEmailJob::dispatch($lead);
        FindLeadLinkedInProfileJob::dispatch($lead);

        return $lead;
    }

    /**
     * Update an existing lead and dispatch relevant background jobs.
     *
     * @param UpdateLeadRequest $request
     * @param Lead $lead
     * @return Lead
     */
    public function updateLead(UpdateLeadRequest $request, Lead $lead): Lead
    {
        $lead->update($request->validated());

        // Dispatch jobs if relevant data has changed
        if ($request->has('email')) {
            VerifyLeadEmailJob::dispatch($lead->fresh());
        }
        if ($request->has('name') || $request->has('company_name')) {
            FindLeadLinkedInProfileJob::dispatch($lead->fresh());
        }

        return $lead;
    }

    /**
     * Update the status of a lead.
     *
     * @param UpdateLeadStatusRequest $request
     * @param Lead $lead
     * @return Lead
     */
    public function updateLeadStatus(UpdateLeadStatusRequest $request, Lead $lead): Lead
    {
        $lead->update($request->validated());
        return $lead;
    }

    /**
     * Delete a lead.
     *
     * @param Lead $lead
     * @return void
     */
    public function deleteLead(Lead $lead): void
    {
        $lead->delete();
    }
}
