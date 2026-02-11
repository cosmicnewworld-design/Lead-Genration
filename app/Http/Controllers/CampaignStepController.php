<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignStep;
use Illuminate\Http\Request;

class CampaignStepController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return view('campaigns.steps.create', compact('campaign'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'delay_in_days' => 'required|integer|min:0',
            'order' => 'required|integer|min:1',
        ]);

        $campaign->steps()->create($request->all());

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign step created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CampaignStep $step)
    {
        $this->authorize('update', $step->campaign);
        return view('campaigns.steps.edit', compact('step'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CampaignStep $step)
    {
        $this->authorize('update', $step->campaign);
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'delay_in_days' => 'required|integer|min:0',
            'order' => 'required|integer|min:1',
        ]);

        $step->update($request->all());

        return redirect()->route('campaigns.show', $step->campaign)
            ->with('success', 'Campaign step updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CampaignStep $step)
    {
        $this->authorize('update', $step->campaign);
        $step->delete();

        return redirect()->route('campaigns.show', $step->campaign)
            ->with('success', 'Campaign step deleted successfully.');
    }
}
