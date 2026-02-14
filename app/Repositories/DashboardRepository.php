<?php

namespace App\Repositories;

use App\Models\Business;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class DashboardRepository
{
    public function getDashboardData()
    {
        $tenant = Auth::user()->tenant;

        $totalBusinesses = $tenant->businesses()->count();
        $totalLeads = $tenant->leads()->count();
        $verifiedLeads = $tenant->leads()->where('status', 'verified')->count();
        $outreachSent = $tenant->businesses()->where('outreach_sent', true)->count();
        $recentLeads = $tenant->leads()->with('business')->latest()->take(5)->get();

        return [
            'totalBusinesses' => $totalBusinesses,
            'totalLeads' => $totalLeads,
            'verifiedLeads' => $verifiedLeads,
            'outreachSent' => $outreachSent,
            'recentLeads' => $recentLeads,
        ];
    }
}
