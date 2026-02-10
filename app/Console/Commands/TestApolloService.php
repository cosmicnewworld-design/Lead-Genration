<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApolloService;

class TestApolloService extends Command
{
    protected $signature = 'apollo:test';
    protected $description = 'Test the ApolloService';

    public function __construct(ApolloService $apolloService)
    {
        parent::__construct();
        $this->apolloService = $apolloService;
    }

    public function handle()
    {
        $leads = $this->apolloService->searchLeads('Purchase Manager', 'Software');
        $this->info(print_r($leads, true));
    }
}
