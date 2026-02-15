<?php

namespace App\Console\Commands;

use App\Events\LeadCreated;
use App\Models\Lead;
use Illuminate\Console\Command;

class TestLeadAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-lead-automation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests the lead automation process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Lead::truncate();

        $lead = Lead::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'source' => 'facebook',
        ]);

        event(new LeadCreated($lead));

        $this->info('Test lead created and event dispatched.');
    }
}
