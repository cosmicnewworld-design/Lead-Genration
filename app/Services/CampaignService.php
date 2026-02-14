<?php

namespace App\Services;

use App\Models\Campaign;
use App\Repositories\CampaignRepository;

class CampaignService
{
    protected $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function getCampaigns()
    {
        return $this->campaignRepository->getCampaigns();
    }

    public function createCampaign(array $data): Campaign
    {
        return $this->campaignRepository->create($data);
    }

    public function updateCampaign(Campaign $campaign, array $data): bool
    {
        return $this->campaignRepository->update($campaign, $data);
    }

    public function deleteCampaign(Campaign $campaign): ?bool
    {
        return $this->campaignRepository->delete($campaign);
    }

    public function getCampaignDataForShow(Campaign $campaign): array
    {
        $leads = $this->campaignRepository->getLeadsForCampaign($campaign);
        return [
            'campaign' => $campaign,
            'leads' => $leads,
        ];
    }
}
