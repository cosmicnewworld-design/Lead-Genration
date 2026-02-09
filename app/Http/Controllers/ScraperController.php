<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function scrape(Request $request)
    {
        $query = $request->input('query', 'web development companies in New York');
        $data = $this->scraperService->searchPlaces($query);

        return response()->json($data);
    }
}
