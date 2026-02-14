<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        $campaigns = $this->campaignService->getCampaigns();
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $this->authorize('create', Campaign::class);
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Campaign::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $this->campaignService->createCampaign($validated);
        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign)
    {
        $this->authorize('view', $campaign);
        $data = $this->campaignService->getCampaignDataForShow($campaign);
        return view('campaigns.show', $data);
    }

    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $this->campaignService->updateCampaign($campaign, $validated);
        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        $this->campaignService->deleteCampaign($campaign);
        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}
