<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Auth::user()->tenant->campaigns;
        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $campaign = Auth::user()->tenant->campaigns()->create($request->all());

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        $this->authorize('view', $campaign);
        $leads = Auth::user()->tenant->leads;
        return view('campaigns.show', compact('campaign', 'leads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $campaign->update($request->all());

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
