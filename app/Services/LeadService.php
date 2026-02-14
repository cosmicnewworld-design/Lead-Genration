<?php

namespace App\Services;

use App\Models\Lead;
use App\Repositories\LeadRepository;
use Illuminate\Support\Facades\Auth;

class LeadService
{
    protected $leadRepository;
    protected $scoringService;

    public function __construct(LeadRepository $leadRepository, LeadScoringService $scoringService)
    {
        $this->leadRepository = $leadRepository;
        $this->scoringService = $scoringService;
    }

    public function getAllLeads()
    {
        return $this->leadRepository->getAll();
    }

    public function getLeadCreationData(): array
    {
        return [
            'campaigns' => Auth::user()->tenant->campaigns()->get(),
        ];
    }

    public function createLead(array $data): Lead
    {
        $lead = $this->leadRepository->create($data);

        if (isset($data['campaign_id'])) {
            $this->leadRepository->attachToCampaign($lead, $data['campaign_id']);
        }

        return $lead;
    }

    public function getLeadDataForShow(Lead $lead): array
    {
        $scoringData = $this->scoringService->calculateScore($lead);
        $lead->score = $scoringData['total_score'];

        return [
            'lead' => $lead,
            'scoreBreakdown' => $scoringData['breakdown'],
        ];
    }

    public function getLeadEditData(Lead $lead): array
    {
        return [
            'lead' => $lead,
            'campaigns' => Auth::user()->tenant->campaigns()->get(),
        ];
    }

    public function updateLead(Lead $lead, array $data): bool
    {
        $updated = $this->leadRepository->update($lead, $data);

        if ($updated && isset($data['campaign_id'])) {
            $this->leadRepository->syncCampaign($lead, $data['campaign_id']);
        }

        return $updated;
    }

    public function deleteLead(Lead $lead): void
    {
        $this->leadRepository->delete($lead);
    }
}
