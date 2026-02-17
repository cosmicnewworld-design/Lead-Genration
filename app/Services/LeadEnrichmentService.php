<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LeadEnrichmentService
{
    /**
     * Enrich lead with data from multiple sources
     */
    public function enrichLead(Lead $lead): array
    {
        $enrichmentData = [];

        // Enrich with email verification
        if ($lead->email) {
            $enrichmentData['email_verification'] = $this->verifyEmail($lead->email);
        }

        // Enrich with company data if company name exists
        if ($lead->company) {
            $enrichmentData['company_data'] = $this->enrichCompanyData($lead->company);
        }

        // Enrich with LinkedIn data if available
        if ($lead->name && $lead->company) {
            $enrichmentData['linkedin'] = $this->findLinkedInProfile($lead->name, $lead->company);
        }

        // Enrich with website data if website exists
        if ($lead->website) {
            $enrichmentData['website_data'] = $this->scrapeWebsite($lead->website);
        }

        // Update lead with enrichment data
        $lead->update([
            'enrichment_data' => array_merge($lead->enrichment_data ?? [], $enrichmentData),
        ]);

        return $enrichmentData;
    }

    /**
     * Verify email address
     */
    public function verifyEmail(string $email): array
    {
        $cacheKey = "email_verification_{$email}";
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($email) {
            try {
                // Check MX records
                $domain = substr(strrchr($email, "@"), 1);
                $mxRecords = [];
                if (getmxrr($domain, $mxRecords)) {
                    return [
                        'valid' => true,
                        'deliverable' => true,
                        'method' => 'mx_record',
                        'checked_at' => now()->toDateTimeString(),
                    ];
                }

                return [
                    'valid' => false,
                    'deliverable' => false,
                    'method' => 'mx_record',
                    'checked_at' => now()->toDateTimeString(),
                ];
            } catch (\Exception $e) {
                Log::error('Email verification error', [
                    'error' => $e->getMessage(),
                    'email' => $email,
                ]);

                return [
                    'valid' => null,
                    'deliverable' => null,
                    'error' => $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Enrich company data
     */
    public function enrichCompanyData(string $companyName): array
    {
        $cacheKey = "company_data_{$companyName}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($companyName) {
            try {
                // You can integrate with APIs like Clearbit, FullContact, etc.
                // For now, return basic structure
                return [
                    'name' => $companyName,
                    'enriched_at' => now()->toDateTimeString(),
                ];
            } catch (\Exception $e) {
                Log::error('Company enrichment error', [
                    'error' => $e->getMessage(),
                    'company' => $companyName,
                ]);

                return [
                    'name' => $companyName,
                    'error' => $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Find LinkedIn profile
     */
    public function findLinkedInProfile(string $name, string $company): ?array
    {
        $cacheKey = "linkedin_{$name}_{$company}";
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($name, $company) {
            try {
                // Note: LinkedIn API requires special permissions
                // This is a placeholder for integration
                return [
                    'name' => $name,
                    'company' => $company,
                    'found' => false,
                    'note' => 'LinkedIn integration requires API access',
                ];
            } catch (\Exception $e) {
                Log::error('LinkedIn lookup error', [
                    'error' => $e->getMessage(),
                    'name' => $name,
                    'company' => $company,
                ]);

                return null;
            }
        });
    }

    /**
     * Scrape website for contact information
     */
    public function scrapeWebsite(string $url): array
    {
        $cacheKey = "website_scrape_{$url}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($url) {
            try {
                // Ensure URL has protocol
                if (!preg_match('/^https?:\/\//', $url)) {
                    $url = 'https://' . $url;
                }

                $response = Http::timeout(10)->get($url);
                
                if (!$response->successful()) {
                    return [
                        'url' => $url,
                        'success' => false,
                        'error' => 'Failed to fetch website',
                    ];
                }

                $html = $response->body();
                
                // Extract email addresses
                preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $html, $emails);
                
                // Extract phone numbers
                preg_match_all('/[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}/', $html, $phones);

                return [
                    'url' => $url,
                    'success' => true,
                    'emails_found' => array_unique($emails[0] ?? []),
                    'phones_found' => array_unique($phones[0] ?? []),
                    'scraped_at' => now()->toDateTimeString(),
                ];
            } catch (\Exception $e) {
                Log::error('Website scraping error', [
                    'error' => $e->getMessage(),
                    'url' => $url,
                ]);

                return [
                    'url' => $url,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Find email address using pattern matching
     */
    public function findEmail(string $firstName, string $lastName, string $domain): ?string
    {
        $patterns = [
            "{$firstName}.{$lastName}@{$domain}",
            "{$firstName}{$lastName}@{$domain}",
            "{$firstName}@{$domain}",
            "{$firstName[0]}.{$lastName}@{$domain}",
            "{$firstName[0]}{$lastName}@{$domain}",
            "{$firstName}.{$lastName[0]}@{$domain}",
        ];

        foreach ($patterns as $email) {
            $verification = $this->verifyEmail($email);
            if ($verification['valid'] ?? false) {
                return $email;
            }
        }

        return null;
    }

    /**
     * Bulk enrich leads
     */
    public function bulkEnrich(array $leadIds): int
    {
        $enriched = 0;
        
        foreach ($leadIds as $leadId) {
            try {
                $lead = Lead::find($leadId);
                if ($lead) {
                    $this->enrichLead($lead);
                    $enriched++;
                }
            } catch (\Exception $e) {
                Log::error('Bulk enrichment error', [
                    'error' => $e->getMessage(),
                    'lead_id' => $leadId,
                ]);
            }
        }

        return $enriched;
    }
}
