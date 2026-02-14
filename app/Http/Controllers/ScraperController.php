<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    /**
     * Initiate a web scraping job for a given query.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function scrape(Request $request): JsonResponse
    {
        try {
            $this->scraperService->startScraping($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Scraping job has been queued successfully. The results will be processed in the background.'
            ], 202);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input.',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
