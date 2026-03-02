<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // The BelongsToTenant trait will automatically scope these queries

        $totalLeads = Lead::count();
        $hotLeadsCount = Lead::where('status', 'Hot')->count();

        // 1. Lead Acquisition Trend (Last 30 days)
        $leadTrend = Lead::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', ' >=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->pluck('count', 'date');

        // Fill in missing dates with 0 counts for a continuous chart
        $trendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trendData[$date] = $leadTrend->get($date, 0);
        }

        // 2. Lead Status Distribution
        $statusDistribution = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // 3. Top Lead Sources
        $topSources = Lead::select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->orderBy('count', 'DESC')
            ->limit(5) // Get top 5 sources
            ->pluck('count', 'source');

        return view('dashboard', [
            'totalLeads' => $totalLeads,
            'hotLeadsCount' => $hotLeadsCount,
            'trendData' => $trendData,
            'statusDistribution' => $statusDistribution,
            'topSources' => $topSources,
        ]);
    }
}
