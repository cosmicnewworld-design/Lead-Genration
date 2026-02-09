<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeGooglePlacesJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScraperController extends Controller
{
    /**
     * Initiate a web scraping job for a given query.
     *
     * This method validates the incoming request for a 'query' parameter,
     * then dispatches a job to the queue to handle the scraping process
     * in the background. It returns an immediate JSON response to the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function scrape(Request $request): JsonResponse
    {
        // Validate the request to ensure a query is present
        $validated = $request->validate([
            'query' => 'required|string|min:3|max:255',
        ]);

        // Dispatch the job to the queue for background processing
        ScrapeGooglePlacesJob::dispatch($validated['query']);

        // Return an immediate response to the user
        return response()->json([
            'status' => 'success',
            'message' => 'Scraping job has been queued successfully. The results will be processed in the background.'
        ], 202); // 202 Accepted is a great status code for this
    }
}
