<?php

namespace App\Http\Controllers;

use App\Events\LeadCreated;
use App\Models\Lead;
use App\Services\LeadScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    protected $scoringService;

    public function __construct(LeadScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'score');
        $sortOrder = $request->get('sort_order', 'desc');
        $status = $request->get('status');

        $validSortColumns = ['name', 'email', 'score'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'score';
        }

        $query = Lead::where('tenant_id', Auth::user()->tenant_id);

        if ($status) {
            $query->where('status', $status);
        }

        $leads = $query->orderBy($sortBy, $sortOrder)->get();

        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ]);

        $validatedData['tenant_id'] = Auth::user()->tenant_id;

        $lead = Lead::create($validatedData);

        $lead->score = $this->scoringService->calculateScore($lead);
        $lead->save();

        event(new LeadCreated($lead));

        return redirect()->route('leads.show', $lead)->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);
        $lead->load('activities.user', 'attachments.user', 'source');
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        return view('leads.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ]);

        $lead->update($validatedData);

        $lead->score = $this->scoringService->calculateScore($lead);
        $lead->save();

        return redirect()->route('leads.show', $lead)->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
}
