<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Lead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBusinesses = Business::count();
        $totalLeads = Lead::count();
        $verifiedLeads = Lead::where('status', 'verified')->count();
        $outreachSent = Business::where('outreach_sent', true)->count();
        $recentLeads = Lead::with('business')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalBusinesses',
            'totalLeads',
            'verifiedLeads',
            'outreachSent',
            'recentLeads'
        ));
    }
}
