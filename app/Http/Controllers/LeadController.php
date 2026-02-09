<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Business;
use App\Services\LeadService;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(Request $request)
    {
        $query = Lead::with('business');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(10);
        $statuses = ['New', 'Contacted', 'Replied', 'Junk'];

        return view('leads.index', compact('leads', 'statuses'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create(Request $request)
    {
        $businesses = Business::all();
        $selectedBusiness = $request->get('business_id');
        return view('leads.create', compact('businesses', 'selectedBusiness'));
    }

    public function store(StoreLeadRequest $request)
    {
        $lead = $this->leadService->createLead($request);

        return redirect()->route('businesses.show', $lead->business_id)
            ->with('success', 'Lead created successfully. Enrichment in progress...');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $businesses = Business::all();
        return view('leads.edit', compact('lead', 'businesses'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $this->leadService->updateLead($request, $lead);

        return redirect()->route('businesses.show', $lead->business_id)
            ->with('success', 'Lead updated successfully. Enrichment in progress...');
    }

    public function destroy(Lead $lead)
    {
        $business_id = $lead->business_id;
        $this->leadService->deleteLead($lead);

        return redirect()->route('businesses.show', $business_id)
            ->with('success', 'Lead deleted successfully');
    }

    public function updateStatus(UpdateLeadStatusRequest $request, Lead $lead)
    {
        $this->leadService->updateLeadStatus($request, $lead);

        return back()->with('success', 'Lead status updated successfully!');
    }
}
