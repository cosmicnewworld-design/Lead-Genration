<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class ScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function findLinkedInProfile(string $name, string $companyName): ?string
    {
        $apiKey = Config::get('services.proxycurl.key');

        if (!$apiKey) {
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
            // Handle the exception (e.g., log the error)
            return null;
        }
    }

    public function verifyEmail(string $email): bool
    {
        $apiKey = Config::get('services.hunter.key');

        if (!$apiKey) {
            return false;
        }

        try {
            $response = $this->client->request('GET', 'https://api.hunter.io/v2/email-verifier', [
                'query' => [
                    'email' => $email,
                    'api_key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return in_array($data['data']['result'] ?? '', ['deliverable', 'risky']);
        } catch (GuzzleException $e) {
            // Handle the exception (e.g., log the error)
            return false;
        }
    }

    private function getFirstName(string $name): string
    {
        $parts = explode(' ', $name);
        return $parts[0] ?? '';
    }

    private function getLastName(string $name): string
    {
        $parts = explode(' ', $name);
        return $parts[1] ?? '';
    }
}
