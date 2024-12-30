<?php

namespace App\Services;

use GuzzleHttp\Client;

class GuardianApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles()
    {
        $response = $this->client->get('https://content.guardianapis.com/search', [
            'query' => [
                'api-key' => env('GUARDIAN_API_KEY'),
                'section' => 'technology',
            ],
        ]);

        return json_decode($response->getBody(), true)['response']['results'];
    }
}
