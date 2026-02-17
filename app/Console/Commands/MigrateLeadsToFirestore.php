<?php

namespace App\Console\Commands;

use App\Models\Lead;
use Illuminate\Console\Command;

class MigrateLeadsToFirestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:migrate-to-firestore {--chunk=100 : Number of records to process at a time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate leads from PostgreSQL/SQLite to Firestore';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chunkSize = $this->option('chunk');
        $firestore = app('firestore');
        $collection = $firestore->collection('leads');

        $this->info('Starting migration of leads to Firestore...');
        $bar = $this->output->createProgressBar(Lead::count());
        $bar->start();

        Lead::with(['tenant', 'pipelineStage', 'assignedTo'])
            ->chunk($chunkSize, function ($leads) use ($collection, $bar) {
                foreach ($leads as $lead) {
                    $data = [
                        'id' => $lead->id,
                        'name' => $lead->name,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                        'company' => $lead->company,
                        'job_title' => $lead->job_title,
                        'website' => $lead->website,
                        'notes' => $lead->notes,
                        'status' => $lead->status,
                        'score' => $lead->score,
                        'pipeline_stage_id' => $lead->pipeline_stage_id,
                        'assigned_to_user_id' => $lead->assigned_to_user_id,
                        'custom_fields' => $lead->custom_fields ?? [],
                        'enrichment_data' => $lead->enrichment_data ?? [],
                        'last_contacted_at' => $lead->last_contacted_at?->toDateTime(),
                        'source' => $lead->source,
                        'lead_source_id' => $lead->lead_source_id,
                        'is_qualified' => $lead->is_qualified,
                        'tenant_id' => $lead->tenant_id,
                        'created_at' => $lead->created_at->toDateTime(),
                        'updated_at' => $lead->updated_at->toDateTime(),
                    ];

                    $collection->document((string)$lead->id)->set($data, ['merge' => true]);
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine();
        $this->info('✅ Successfully migrated all leads to Firestore!');

        return Command::SUCCESS;
    }
}
