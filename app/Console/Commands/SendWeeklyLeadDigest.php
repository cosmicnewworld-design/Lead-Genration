<?php

namespace App\Console\Commands;

use App\Mail\WeeklyLeadDigest;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendWeeklyLeadDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:send-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a weekly digest of high-scoring leads to the sales team.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fetching high-scoring leads from the past week...');

        // 1. Fetch top 10 leads created in the last week
        $leads = Lead::where('created_at', '>=', Carbon::now()->subWeek())
                    ->orderBy('score', 'desc')
                    ->take(10)
                    ->get();

        if ($leads->isEmpty()) {
            $this->info('No new high-scoring leads in the past week. No email will be sent.');
            return 0;
        }

        // 2. Define the recipient (for now, it's hardcoded)
        // In a real app, this would come from a config file or user settings
        $recipient = 'sales-manager@example.com';

        // 3. Send the email
        $this->info("Sending digest to {$recipient}...");
        Mail::to($recipient)->send(new WeeklyLeadDigest($leads));

        $this->info('Weekly lead digest sent successfully!');
        return 0;
    }
}
