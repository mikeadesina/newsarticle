<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsApiService implements ArticleFetcherInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles(): array
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

    public function getSourceName(): string
    {
        return 'NewsAPI';
    }
}
