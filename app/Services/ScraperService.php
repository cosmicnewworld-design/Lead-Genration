<?php

namespace App\Services;

use App\Jobs\ScrapeGooglePlacesJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ScraperService
{
    /**
     * Validate the query and dispatch the scraping job.
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function startScraping(array $data): void
    {
        $validator = Validator::make($data, [
            'query' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        ScrapeGooglePlacesJob::dispatch($validator->validated()['query']);
    }
}
