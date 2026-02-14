<?php

namespace App\Repositories;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadRepository
{
    public function getAll()
    {
        // Global scope should handle tenant filtering
        return Lead::latest()->get();
    }

    public function create(array $data): Lead
    {
        $data['tenant_id'] = Auth::user()->tenant_id;
        if (Auth::user()->tenant->business) {
            $data['business_id'] = Auth::user()->tenant->business->id;
        }
        
        // The controller validates the data, so it should be safe for mass assignment
        return Lead::create($data);
    }
    
    public function update(Lead $lead, array $data): bool
    {
        return $lead->update($data);
    }

    public function attachToCampaign(Lead $lead, int $campaignId): void
    {
        $lead->campaigns()->syncWithoutDetaching([$campaignId]);
    }

    public function syncCampaign(Lead $lead, ?int $campaignId): void
    {
        $lead->campaigns()->sync($campaignId ? [$campaignId] : []);
    }

    public function delete(Lead $lead): void
    {
        $lead->delete();
    }
}
