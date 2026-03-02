<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadSource;
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

    public function getAllLeads(string $searchTerm = null)
    {
        return $this->leadRepository->getAll($searchTerm);
    }

    public function getLeadCreationData(): array
    {
        return [
            'campaigns' => Auth::user()->tenant->campaigns()->get(),
            'leadSources' => LeadSource::where('tenant_id', Auth::user()->tenant_id)
                ->where('is_active', true)
                ->with(['category'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->groupBy(fn ($s) => $s->category?->name ?? 'Uncategorized'),
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
            'leadSources' => LeadSource::where('tenant_id', Auth::user()->tenant_id)
                ->where('is_active', true)
                ->with(['category'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->groupBy(fn ($s) => $s->category?->name ?? 'Uncategorized'),
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
