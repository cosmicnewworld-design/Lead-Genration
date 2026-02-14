<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class CampaignRepository
{
    public function getCampaigns()
    {
        return Auth::user()->tenant->campaigns()->latest()->paginate(10);
    }

    public function create(array $data): Campaign
    {
        return Auth::user()->tenant->campaigns()->create($data);
    }

    public function update(Campaign $campaign, array $data): bool
    {
        return $campaign->update($data);
    }

    public function delete(Campaign $campaign): ?bool
    {
        return $campaign->delete();
    }

    public function getLeadsForCampaign(Campaign $campaign)
    {
        return $campaign->leads()->get();
    }
}
