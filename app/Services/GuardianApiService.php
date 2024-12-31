<?php

namespace App\Services;

use GuzzleHttp\Client;

class GuardianApiService implements ArticleFetcherInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles(): array
    {
        $response = $this->client->get('https://content.guardianapis.com/search', [
            'query' => [
                'api-key' => env('GUARDIAN_API_KEY'),
                'section' => 'technology',
            ]
        ]);

        $articles = json_decode($response->getBody(), true)['response']['results'];
        return array_map(function ($article) {
            $article['url'] = $article['webUrl'];
            unset($article['webUrl']);
            return $article;
        }, $articles);
    }

    public function getSourceName(): string
    {
        return 'The Guardian';
    }
}
