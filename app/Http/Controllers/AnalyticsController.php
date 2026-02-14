<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function showLeadScoreDistribution()
    {
        return view('analytics.lead-score-distribution');
    }

    public function leadScoreDistribution()
    {
        $scores = Lead::pluck('score')->toArray();

        $scoreDistribution = array_count_values($scores);

        return response()->json($scoreDistribution);
    }
}
