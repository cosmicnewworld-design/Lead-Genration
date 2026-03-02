<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getDashboardData()
    {
        // Fetch core stats
        $totalLeads = Lead::count();
        $totalCampaigns = Campaign::count();
        $recentLeads = Lead::with('campaign')->latest()->take(5)->get();

        // Prepare data for the pie chart
        $leadsPerCampaign = Campaign::withCount('leads')
            ->having('leads_count', '>', 0)
            ->orderBy('leads_count', 'desc')
            ->get();

        $campaignLabels = $leadsPerCampaign->pluck('name')->all();
        $campaignLeadData = $leadsPerCampaign->pluck('leads_count')->all();

        return [
            'totalLeads' => $totalLeads,
            'totalCampaigns' => $totalCampaigns,
            'recentLeads' => $recentLeads,
            'campaignLabels' => $campaignLabels,
            'campaignLeadData' => $campaignLeadData,
        ];
    }
}
