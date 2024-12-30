<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles()
    {
        $response = $this->client->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => env('NEWS_API_KEY'),
                'category' => 'technology',
                'country' => 'us',
            ]
        ]);
        return json_decode($response->getBody(), true)['articles'];
    }
}
