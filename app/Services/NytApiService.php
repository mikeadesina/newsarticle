<?php
namespace App\Services;
use GuzzleHttp\Client;
class NytApiService implements ArticleFetcherInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles(): array
    {
        $response = $this->client->get('https://api.nytimes.com/svc/topstories/v2/technology.json', [
            'query' => [
                'api-key' => env('NYT_API_KEY'),
            ],
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    public function getSourceName(): string
    {
        return 'The New York Times';
    }
}


