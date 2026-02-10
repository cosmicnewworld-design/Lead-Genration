<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\Business;
use App\Mail\OutreachEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendFollowUps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-follow-ups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send follow-up emails to leads who have not replied.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for leads to follow up with...');

        $leads = Lead::with('business')
            ->where('status', '!=', 'Replied')
            ->where('status', '!=', 'Unsubscribed')
            ->where('follow_up_count', '<', 3)
            ->where(function ($query) {
                $query->whereNull('last_follow_up_sent_at')
                      ->orWhere('last_follow_up_sent_at', '<=', Carbon::now()->subDays(3));
            })
            ->get();

        if ($leads->isEmpty()) {
            $this->info('No leads to follow up with at this time.');
            return 0;
        }

        $this->info("Found {$leads->count()} leads to follow up with.");

        foreach ($leads as $lead) {
            $email = data_get($lead->scraped_data, 'email') ?? data_get($lead->scraped_data, 'contact_info.email');

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->warn("Skipping lead {$lead->name} ({$lead->id}) - no valid email found.");
                continue;
            }

            $businessName = $lead->business->name ?? 'our team';

            $subject = 'Following up from ' . $businessName;
            
            $message = "Hi {$lead->name},\n\nJust wanted to gently follow up on my previous message regarding your company, {$lead->company_name}.\n\nWe specialize in helping businesses like yours. Is this something you're interested in exploring?\n\nBest regards,\nThe {$businessName} Team";

            $this->info("Sending follow-up #" . ($lead->follow_up_count + 1) . " to: {$lead->name} at {$email}");

            Mail::to($email)->send(new OutreachEmail($subject, nl2br(e($message))));

            $lead->update([
                'follow_up_count' => $lead->follow_up_count + 1,
                'last_follow_up_sent_at' => Carbon::now(),
            ]);
        }

        $this->info('All follow-ups have been sent successfully.');
        return 0;
    }
}
