<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Models\LeadSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository
{
    public function getAll(string $searchTerm = null): LengthAwarePaginator
    {
        // Global scope should handle tenant filtering
        $query = Lead::latest();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        return $query->paginate(15); // Paginate with 15 items per page
    }

    public function create(array $data): Lead
    {
        $data['tenant_id'] = Auth::user()->tenant_id;
        if (Auth::user()->tenant->business) {
            $data['business_id'] = Auth::user()->tenant->business->id;
        }

        // If lead_source_id is provided, copy a human readable label into `source`
        if (!empty($data['lead_source_id'])) {
            $leadSource = LeadSource::where('tenant_id', $data['tenant_id'])
                ->where('id', $data['lead_source_id'])
                ->first();
            if ($leadSource) {
                $data['source'] = $leadSource->name;
            }
        }

        // De-dupe: if same email already exists for tenant, update it instead of creating a duplicate
        if (!empty($data['email'])) {
            $existing = Lead::where('tenant_id', $data['tenant_id'])->where('email', $data['email'])->first();
            if ($existing) {
                $existing->update($data);
                return $existing;
            }
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
