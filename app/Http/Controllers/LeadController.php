<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Business;
use App\Services\ScraperService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $businesses = Business::all();
        $selectedBusiness = $request->get('business_id');
        return view('leads.create', compact('businesses', 'selectedBusiness'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:New,Contacted,Replied,Junk',
            'business_id' => 'required|exists:businesses,id',
            'email' => 'nullable|email',
        ]);

        $leadData = $request->all();

        if ($request->has('email')) {
            $isEmailValid = $this->scraperService->verifyEmail($request->email);
            if ($isEmailValid) {
                $leadData['email_verified_at'] = now();
            }
        }

        if ($request->has('name') && $request->has('company_name')) {
            $leadData['linkedin_profile'] = $this->scraperService->findLinkedInProfile($request->name, $request->company_name);
        }

        Lead::create($leadData);

        return redirect()->route('businesses.show', $request->business_id)
            ->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $businesses = Business::all();
        return view('leads.edit', compact('lead', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:New,Contacted,Replied,Junk',
            'business_id' => 'required|exists:businesses,id',
            'email' => 'nullable|email',
        ]);

        $leadData = $request->all();

        if ($request->has('email') && $request->email !== $lead->email) {
            $isEmailValid = $this->scraperService->verifyEmail($request->email);
            $leadData['email_verified_at'] = $isEmailValid ? now() : null;
        }

        if (($request->has('name') && $request->name !== $lead->name) || ($request->has('company_name') && $request->company_name !== $lead->company_name)) {
            $leadData['linkedin_profile'] = $this->scraperService->findLinkedInProfile($request->name, $request->company_name);
        }

        $lead->update($leadData);

        return redirect()->route('businesses.show', $request->business_id)
            ->with('success', 'Lead updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $business_id = $lead->business_id;
        $lead->delete();

        return redirect()->route('businesses.show', $business_id)
            ->with('success', 'Lead deleted successfully');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:New,Contacted,Replied,Junk',
        ]);

        $lead->update($validated);

        return back()->with('success', 'Lead status updated successfully!');
    }

}
