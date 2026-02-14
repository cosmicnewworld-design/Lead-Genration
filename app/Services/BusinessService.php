<?php

namespace App\Services;

use App\Models\Business;
use App\Repositories\BusinessRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\OutreachEmail;
use App\Services\AiService;
use Illuminate\Support\Facades\Auth;

class BusinessService
{
    protected $businessRepository;
    protected $aiService;

    public function __construct(BusinessRepository $businessRepository, AiService $aiService)
    {
        $this->businessRepository = $businessRepository;
        $this->aiService = $aiService;
    }

    public function getAllBusinesses()
    {
        return $this->businessRepository->getAll();
    }

    public function createBusiness(array $data)
    {
        $data['tenant_id'] = Auth::user()->tenant_id;
        return $this->businessRepository->create($data);
    }

    public function getBusinessById($id)
    {
        return $this->businessRepository->findWithLeads($id);
    }

    public function updateBusiness(Business $business, array $data)
    {
        return $this->businessRepository->update($business, $data);
    }

    public function deleteBusiness(Business $business)
    {
        return $this->businessRepository->delete($business);
    }
    
    public function sendOutreach(Business $business, string $subject, string $messageTemplate)
    {
        $leads = $business->leads;
        $personalizedMessages = [];

        foreach ($leads as $lead) {
            $personalizedMessage = $this->aiService->personalizeMessage($messageTemplate, $lead->scraped_data);
            $personalizedMessages[] = ['body' => $personalizedMessage];
            
            Mail::to($lead->email)->send(new OutreachEmail($subject, $personalizedMessage));
        }
        
        return $personalizedMessages;
    }
}
