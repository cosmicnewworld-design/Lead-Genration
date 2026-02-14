<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index()
    {
        $leads = $this->leadService->getAllLeads();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $data = $this->leadService->getLeadCreationData();
        return view('leads.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'source' => 'nullable|string|max:255',
        ]);

        $this->leadService->createLead($request->all());

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);
        $data = $this->leadService->getLeadDataForShow($lead);
        return view('leads.show', $data);
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        $data = $this->leadService->getLeadEditData($lead);
        return view('leads.edit', $data);
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'source' => 'nullable|string|max:255',
        ]);

        $this->leadService->updateLead($lead, $request->all());

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $this->leadService->deleteLead($lead);
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
}
