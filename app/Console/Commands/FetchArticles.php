<?php

namespace App\Console\Commands;
use App\Models\Article;
use App\Services\NewsApiService;
use App\Services\GuardianApiService;
use App\Services\NytApiService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $newsApiService;
    protected $guardianApiService;

    /**
     * Execute the console command.
     */
    public function __construct(
        NewsApiService $newsApiService,
        GuardianApiService $guardianApiService,
        NytApiService $nytApiService

    ) {
        parent::__construct();
        $this->newsApiService = $newsApiService;
        $this->guardianApiService = $guardianApiService;
        $this->nytApiService = $nytApiService;



    }
    public function handle()
    {
        $this->info("Fetching articles...");

        $newsApiArticles = $this->newsApiService->fetchArticles();
        $guardianArticles = $this->guardianApiService->fetchArticles();
        $nytArticles = $this->nytApiService->fetchArticles();
        $allArticles = array_merge(
            $this->transformNewsApiArticles($newsApiArticles),
            $this->transformGuardianArticles($guardianArticles),
            $this->transformNytArticles($nytArticles)
        );
        foreach ($allArticles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                $article
            );
        }
        $this->info("Articles fetched and stored successfully!");
    }
    private function transformNewsApiArticles($articles)
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'description' => isset($article['description']) ? $article['description'] : 'No description available',
                'author' => isset($article['author']) ? $article['author'] : 'Unknown',
                'source' => $article['source']['name'],
                'category' => 'Technology',
                'published_at' => Carbon::parse($article['publishedAt'])->toDateTimeString(),
                'url' => $article['url'],
            ];
        }, $articles);
    }

    private function transformGuardianArticles($articles)
    {
        return array_map(function ($article) {
            return [
                'title' => $article['webTitle'],
                'description' => 'No description available',
                'author' => 'Unknown',
                'source' => 'The Guardian',
                'category' => 'Technology',
                'published_at' => Carbon::parse($article['webPublicationDate'])->toDateTimeString(),
                'url' => $article['webUrl'],
            ];
        }, $articles);
    }
    private function transformNytArticles($articles)
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'description' => isset($article['abstract']) ? $article['abstract'] : 'No description available',
                'author' => isset($article['byline']) ? $article['byline'] : 'Unknown',
                'source' => 'New York Times',
                'category' => 'Technology',
                'published_at' => Carbon::parse($article['published_date'])->toDateTimeString(),
                'url' => $article['url'],
            ];
        }, $articles);
    }
}
