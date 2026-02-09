
<?php

namespace App\Services;

use App\Jobs\EnrichLeadJob;
use App\Models\Business;
use App\Models\Lead;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ScraperService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Searches Google Places for businesses matching a query.
     *
     * @param string $query
     * @return void
     */
    public function searchPlaces(string $query): void
    {
        $this->serpApiSearch($query, 'google', function ($result) {
            if (isset($result['place_id'])) {
                $this->getPlaceDetails($result['place_id']);
            }
        });
    }

    /**
     * Gets the details of a place from Google Maps.
     *
     * @param string $placeId
     * @return void
     */
    public function getPlaceDetails(string $placeId): void
    {
        $this->serpApiSearch($placeId, 'google_maps', function ($placeData) {
            $business = Business::updateOrCreate(
                ['google_maps_url' => $placeData['link'] ?? null],
                [
                    'name' => $placeData['title'] ?? null,
                    'website' => $placeData['website'] ?? null,
                    'address' => $placeData['address'] ?? null,
                    'phone' => $placeData['phone'] ?? null,
                    'description' => $placeData['description']['snippet'] ?? null,
                ]
            );

            $this->createLeadFromBusiness($business);
        }, 'place_id');
    }

    /**
     * Finds the email address of a person.
     *
     * @param string $name
     * @param string $domain
     * @return string|null
     */
    public function findEmail(string $name, string $domain): ?string
    {
        return $this->hunterApiRequest('email-finder', [
            'domain' => $domain,
            'full_name' => $name,
        ], 'email');
    }

    /**
     * Verifies an email address.
     *
     * @param string $email
     * @return bool
     */
    public function verifyEmail(string $email): bool
    {
        $result = $this->hunterApiRequest('email-verifier', ['email' => $email], 'result');

        return in_array($result, ['deliverable', 'risky']);
    }

    /**
     * Finds the social media profiles on a website.
     *
     * @param string $url
     * @return array
     */
    public function findSocials(string $url): array
    {
        if (!$url) {
            return [];
        }

        try {
            $response = $this->client->request('GET', $url);
            $html = $response->getBody()->getContents();
            $socials = [];

            $patterns = [
                'linkedin' => '/https?:\/\/(www\.)?linkedin\.com\/(in|company)\/[a-zA-Z0-9-]+\/?/',
                'twitter' => '/https?:\/\/(www\.)?twitter\.com\/[a-zA-Z0-9_]+\/?/',
                'facebook' => '/https?:\/\/(www\.)?facebook\.com\/[a-zA-Z0-9.]+\/?/',
                'instagram' => '/https?:\/\/(www\.)?instagram\.com\/[a-zA-Z0-9_.]+\/?/',
            ];

            foreach ($patterns as $key => $pattern) {
                if (preg_match($pattern, $html, $matches)) {
                    $socials[$key] = $matches[0];
                }
            }

            return $socials;
        } catch (GuzzleException $e) {
            Log::error('Failed to find socials for ' . $url . ': ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Finds the LinkedIn profile of a person.
     *
     * @param string $name
     * @param string $companyName
     * @return string|null
     */
    public function findLinkedInProfile(string $name, string $companyName): ?string
    {
        $apiKey = Config::get('services.proxycurl.key');

        if (!$apiKey) {
            Log::error('Proxycurl API key is not configured.');
            return null;
        }

        try {
            $response = $this->client->request('GET', 'https://nubela.co/proxycurl/api/v2/linkedin', [
                'query' => [
                    'first_name' => $this->getFirstName($name),
                    'last_name' => $this->getLastName($name),
                    'company_name' => $companyName,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['linkedin_profile_url'] ?? null;
        } catch (GuzzleException $e) {
            Log::error('Proxycurl request failed: ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Performs a search using the SerpApi.
     *
     * @param string $query
     * @param string $engine
     * @param callable $callback
     * @param string $queryParam
     * @return void
     */
    private function serpApiSearch(string $query, string $engine, callable $callback, string $queryParam = 'q'): void
    {
        $apiKey = Config::get('services.serpapi.key');

        if (!$apiKey) {
            Log::error('SerpApi API key is not configured.');
            return;
        }

        try {
            $response = $this->client->request('GET', 'https://serpapi.com/search', [
                'query' => [
                    'engine' => $engine,
                    $queryParam => $query,
                    'api_key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($engine === 'google_maps') {
                $results = $data['place_results'] ?? null;
                if($results) $callback($results);
            } else {
                $results = array_merge($data['organic_results'] ?? [], $data['local_results'] ?? []);
                foreach ($results as $result) {
                    $callback($result);
                }
            }


        } catch (GuzzleException $e) {
            Log::error('SerpApi search failed: ' . $e->getMessage());
        }
    }

    /**
     * Makes a request to the Hunter.io API.
     *
     * @param string $endpoint
     * @param array $params
     * @param string $dataKey
     * @return mixed
     */
    private function hunterApiRequest(string $endpoint, array $params, string $dataKey)
    {
        $apiKey = Config::get('services.hunter.key');

        if (!$apiKey) {
            Log::error('Hunter.io API key is not configured.');
            return null;
        }

        try {
            $response = $this->client->request('GET', "https://api.hunter.io/v2/{$endpoint}", [
                'query' => array_merge($params, ['api_key' => $apiKey]),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['data'][$dataKey] ?? null;
        } catch (GuzzleException $e) {
            Log::error('Hunter.io request failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Creates a lead from a business.
     *
     * @param Business $business
     * @return void
     */
    private function createLeadFromBusiness(Business $business): void
    {
        $lead = Lead::firstOrCreate(
            [
                'business_id' => $business->id,
            ],
            [
                'name' => $business->name,
                'email' => 'placeholder@example.com', // We will enrich this later
                'phone_number' => $business->phone ?? null,
                'status' => 'new',
            ]
        );

        EnrichLeadJob::dispatch($lead);
    }

    /**
     * Gets the first name from a full name.
     *
     * @param string $name
     * @return string
     */
    private function getFirstName(string $name): string
    {
        $parts = explode(' ', $name);
        return $parts[0] ?? '';
    }

    /**
     * Gets the last name from a full name.
     *
     * @param string $name
     * @return string
     */
    private function getLastName(string $name): string
    {
        $parts = explode(' ', $name);
        return $parts[1] ?? '';
    }
}
