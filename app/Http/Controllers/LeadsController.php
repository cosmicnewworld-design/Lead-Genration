<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Campaign;
use App\Services\LeadScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    protected $scoringService;

    public function __construct(LeadScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Auth::user()->tenant->leads()->latest()->get();

        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campaigns = Auth::user()->tenant->campaigns()->get();
        return view('leads.create', compact('campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'source' => 'nullable|string|max:255',
        ]);

        $lead = new Lead();
        $lead->name = $request->name;
        $lead->email = $request->email;
        $lead->source = $request->source;
        $lead->tenant_id = Auth::user()->tenant_id;
        $lead->save();

        if ($request->campaign_id) {
            $lead->campaigns()->attach($request->campaign_id);
        }

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $scoringData = $this->scoringService->calculateScore($lead);
        $lead->score = $scoringData['total_score'];
        $scoreBreakdown = $scoringData['breakdown'];

        return view('leads.show', compact('lead', 'scoreBreakdown'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
}
