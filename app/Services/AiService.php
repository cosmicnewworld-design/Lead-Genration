<?php

namespace App\Services;

use GuzzleHttp\Client;

class AiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function analyzeContent($content)
    {
        $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that analyzes content.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $content
                    ]
                ]
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
