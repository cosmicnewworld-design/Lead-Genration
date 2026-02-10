<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Business;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $leads = Lead::with('business')->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('leads.index', compact('leads', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $businesses = Business::where('tenant_id', config('tenant.id'))->get();
        return view('leads.create', compact('businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request)
    {
        $lead = new Lead($request->validated());
        $lead->tenant_id = config('tenant.id');
        $lead->save();

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
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
        $businesses = Business::where('tenant_id', config('tenant.id'))->get();
        return view('leads.edit', compact('lead', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLeadRequest $request, Lead $lead)
    {
        $lead->update($request->validated());
        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
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
