<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Services\LeadImportService;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    protected $leadService;
    protected $leadImportService;

    public function __construct(LeadService $leadService, LeadImportService $leadImportService)
    {
        $this->leadService = $leadService;
        $this->leadImportService = $leadImportService;
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

    public function import()
    {
        $data = $this->leadService->getLeadCreationData();
        return view('leads.import', $data);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:20480',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'source' => 'nullable|string|max:255',
        ]);

        $defaults = [];
        if ($request->filled('lead_source_id')) {
            $defaults['lead_source_id'] = (int) $request->lead_source_id;
        }
        if ($request->filled('source')) {
            $defaults['source'] = (string) $request->source;
        } elseif ($request->filled('lead_source_id')) {
            $defaults['source'] = LeadSource::where('id', $request->lead_source_id)->value('name');
        }

        $result = $this->leadImportService->importCsv($request->file('file'), $defaults);

        return redirect()
            ->route('leads.index')
            ->with('success', "CSV import done. Imported: {$result['imported']}, Skipped: {$result['skipped']}");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'source' => 'nullable|string|max:255',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
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
            'lead_source_id' => 'nullable|exists:lead_sources,id',
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
