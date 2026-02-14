<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Services\LeadScoringService;
use Illuminate\Console\Command;

class ScoreLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Score all leads based on scoring rules';

    /**
     * Execute the console command.
     */
    public function handle(LeadScoringService $scoringService)
    {
        $leads = Lead::all();

        foreach ($leads as $lead) {
            $scoringService->updateLeadScore($lead);
        }

        $this->info('All leads have been scored successfully!');
    }
}
