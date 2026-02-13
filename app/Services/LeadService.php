<?php

namespace App\Services;

use App\Repositories\LeadRepository;
use App\Models\Lead;

class LeadService
{
    protected $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function getAllLeads()
    {
        return $this->leadRepository->getAll();
    }

    public function createLead(array $data)
    {
        // Add any business logic here before creating the lead
        return $this->leadRepository->create($data);
    }

    public function updateLead(Lead $lead, array $data)
    {
        // Add any business logic here before updating the lead
        return $this->leadRepository->update($lead, $data);
    }

    public function deleteLead(Lead $lead)
    {
        // Add any business logic here before deleting the lead
        return $this->leadRepository->delete($lead);
    }
}
