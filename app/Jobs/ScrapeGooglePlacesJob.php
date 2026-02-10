<?php

namespace App\Jobs;

use App\Services\ScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeGooglePlacesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The search query for Google Places.
     *
     * @var string
     */
    protected $query;

    /**
     * Create a new job instance.
     *
     * @param string $query
     * @return void
     */
    public function __construct(string $query)
    {
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @param ScraperService $scraperService
     * @return void
     */
    public function handle(ScraperService $scraperService): void
    {
        try {
            Log::info("Starting Google Places scrape for query: {$this->query}");

            $scraperService->searchPlaces($this->query);

            Log::info("Successfully completed Google Places scrape for query: {$this->query}");
        } catch (\Exception $e) {
            Log::error("Failed Google Places scrape for query: {$this->query}. Error: " . $e->getMessage());
            // Optionally, you can re-throw the exception to have the job fail and be retried
            // throw $e;
        }
    }
}
