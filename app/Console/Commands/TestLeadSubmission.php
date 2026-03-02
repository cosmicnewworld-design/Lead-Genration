<?php

namespace App\Console\Commands;

use App\Events\LeadCreated;
use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestLeadSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-lead-submission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulates a new lead submission for testing purposes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->error('No tenants found in the database. Please create a tenant first.');
            return 1;
        }

        // Set the current tenant for this command's execution context
        $tenant->makeCurrent();

        $lead = new Lead([
            'name' => 'Test User',
            'email' => 'test.' . time() . '@example.com',
            'phone' => '123-456-7890',
            'status' => 'new',
            'tenant_id' => $tenant->id,
        ]);
        $lead->save();

        $this->info('Created a new test lead: ' . $lead->email);

        // Dispatch the event to trigger the email
        LeadCreated::dispatch($lead);

        $this->info('LeadCreated event dispatched. Check the log for the email content.');

        // Forget the current tenant when the command is done
        Tenant::forgetCurrent();

        return 0;
    }
}
