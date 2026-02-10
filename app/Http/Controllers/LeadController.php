<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('campaign')->latest()->paginate(10);
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $this->authorize('create', Lead::class);
        $campaigns = Campaign::all();
        return view('leads.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lead::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('leads')->where(function ($query) {
                return $query->where('tenant_id', auth()->user()->tenant_id);
            })],
            'phone' => 'nullable|string',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);
        auth()->user()->tenant->leads()->create($validated);
        return redirect()->route('leads.index')->with('success', 'Lead created.');
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        $campaigns = Campaign::all();
        return view('leads.edit', compact('lead', 'campaigns'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('leads')->ignore($lead->id)->where(function ($query) {
                return $query->where('tenant_id', auth()->user()->tenant_id);
            })],
            'phone' => 'nullable|string',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);
        $lead->update($validated);
        return redirect()->route('leads.index')->with('success', 'Lead updated.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->delete();
        return back()->with('success', 'Lead deleted.');
    }
}
