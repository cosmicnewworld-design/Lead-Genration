<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Campaign;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get dashboard analytics for a tenant
     */
    public function getDashboardAnalytics(Tenant $tenant): array
    {
        return [
            'leads' => $this->getLeadAnalytics($tenant),
            'campaigns' => $this->getCampaignAnalytics($tenant),
            'conversions' => $this->getConversionAnalytics($tenant),
            'recent_activity' => $this->getRecentActivity($tenant),
        ];
    }

    /**
     * Get lead analytics
     */
    public function getLeadAnalytics(Tenant $tenant): array
    {
        $totalLeads = Lead::where('tenant_id', $tenant->id)->count();
        $newLeads = Lead::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        $qualifiedLeads = Lead::where('tenant_id', $tenant->id)
            ->where('is_qualified', true)
            ->count();
        $highScoreLeads = Lead::where('tenant_id', $tenant->id)
            ->where('score', '>=', 50)
            ->count();

        $leadsByStatus = Lead::where('tenant_id', $tenant->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $leadsBySource = Lead::where('tenant_id', $tenant->id)
            ->whereNotNull('source')
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        return [
            'total' => $totalLeads,
            'new_last_30_days' => $newLeads,
            'qualified' => $qualifiedLeads,
            'high_score' => $highScoreLeads,
            'by_status' => $leadsByStatus,
            'by_source' => $leadsBySource,
        ];
    }

    /**
     * Get campaign analytics
     */
    public function getCampaignAnalytics(Tenant $tenant): array
    {
        $totalCampaigns = Campaign::where('tenant_id', $tenant->id)->count();
        $activeCampaigns = Campaign::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->count();

        $campaigns = Campaign::where('tenant_id', $tenant->id)
            ->withCount('leads')
            ->get();

        $totalSent = 0;
        $totalOpened = 0;
        $totalClicked = 0;
        $totalReplied = 0;

        foreach ($campaigns as $campaign) {
            $stats = $campaign->stats;
            $totalSent += $stats['sent'];
            $totalOpened += $stats['opened'];
            $totalClicked += $stats['clicked'];
            $totalReplied += $stats['replied'];
        }

        $openRate = $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 2) : 0;
        $clickRate = $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 2) : 0;
        $replyRate = $totalSent > 0 ? round(($totalReplied / $totalSent) * 100, 2) : 0;

        return [
            'total' => $totalCampaigns,
            'active' => $activeCampaigns,
            'total_sent' => $totalSent,
            'total_opened' => $totalOpened,
            'total_clicked' => $totalClicked,
            'total_replied' => $totalReplied,
            'open_rate' => $openRate,
            'click_rate' => $clickRate,
            'reply_rate' => $replyRate,
        ];
    }

    /**
     * Get conversion analytics
     */
    public function getConversionAnalytics(Tenant $tenant): array
    {
        $leads = Lead::where('tenant_id', $tenant->id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $conversions = Lead::where('tenant_id', $tenant->id)
            ->where('is_qualified', true)
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('updated_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'leads_over_time' => $leads->pluck('count', 'date')->toArray(),
            'conversions_over_time' => $conversions->pluck('count', 'date')->toArray(),
        ];
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(Tenant $tenant, int $limit = 10): array
    {
        $recentLeads = Lead::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'company', 'created_at']);

        $recentCampaigns = Campaign::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'status', 'created_at']);

        return [
            'recent_leads' => $recentLeads,
            'recent_campaigns' => $recentCampaigns,
        ];
    }

    /**
     * Get lead score distribution
     */
    public function getLeadScoreDistribution(Tenant $tenant): array
    {
        $distribution = Lead::where('tenant_id', $tenant->id)
            ->selectRaw('
                CASE
                    WHEN score >= 80 THEN "High (80+)"
                    WHEN score >= 50 THEN "Medium (50-79)"
                    WHEN score >= 25 THEN "Low (25-49)"
                    ELSE "Very Low (<25)"
                END as score_range,
                COUNT(*) as count
            ')
            ->groupBy('score_range')
            ->pluck('count', 'score_range')
            ->toArray();

        return $distribution;
    }

    /**
     * Get campaign performance comparison
     */
    public function getCampaignPerformance(Tenant $tenant): array
    {
        $campaigns = Campaign::where('tenant_id', $tenant->id)
            ->with('leads')
            ->get();

        $performance = [];

        foreach ($campaigns as $campaign) {
            $stats = $campaign->stats;
            $performance[] = [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
                'total_leads' => $stats['total_leads'],
                'sent' => $stats['sent'],
                'opened' => $stats['opened'],
                'clicked' => $stats['clicked'],
                'replied' => $stats['replied'],
                'open_rate' => $stats['open_rate'],
                'click_rate' => $stats['click_rate'],
                'reply_rate' => $stats['reply_rate'],
            ];
        }

        return $performance;
    }
}
