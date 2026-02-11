<?php

namespace App\Jobs;

use App\Models\CampaignRun;
use App\Models\CampaignStep;
use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;
    public $campaignRun;
    public $lead;
    public $campaignStep;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, CampaignRun $campaignRun, Lead $lead, CampaignStep $campaignStep)
    {
        $this->tenant = $tenant;
        $this->campaignRun = $campaignRun;
        $this->lead = $lead;
        $this->campaignStep = $campaignStep;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Set the tenant for this job
        tenancy()->initialize($this->tenant);

        // 1. Send the email
        // Note: In a real application, you'd use a Mailable class
        Mail::raw($this->campaignStep->body, function ($message) {
            $message->to($this->lead->email)
                ->subject($this->campaignStep->subject);
        });

        // 2. Update the pivot table to mark this step as processed
        $this->campaignRun->leads()->updateExistingPivot($this->lead->id, [
            'current_step_id' => $this->campaignStep->id,
            'status' => 'sent',
            'processed_at' => now(),
        ]);

        // 3. Find the next step
        $nextStep = CampaignStep::where('campaign_id', $this->campaignStep->campaign_id)
            ->where('order', ' > ', $this->campaignStep->order)
            ->orderBy('order', 'asc')
            ->first();

        // 4. If there is a next step, dispatch a new job for it
        if ($nextStep) {
            $delay = now()->addDays($nextStep->delay_in_days);
            self::dispatch($this->tenant, $this->campaignRun, $this->lead, $nextStep)->delay($delay);
        } else {
            // 5. If there are no more steps, mark the lead as completed in this campaign run
            $this->campaignRun->leads()->updateExistingPivot($this->lead->id, [
                'status' => 'completed',
            ]);

            // Optional: Check if all leads in the run are completed, and if so, mark the run as completed.
            $allLeadsCompleted = $this->campaignRun->leads()->wherePivot('status', '!=', 'completed')->count() === 0;
            if ($allLeadsCompleted) {
                $this->campaignRun->update(['status' => 'completed']);
            }
        }
    }
}
