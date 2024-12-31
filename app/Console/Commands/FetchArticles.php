<?php
namespace App\Console\Commands;
use App\Services\ArticleStorageService;
use App\Services\NewsApiService;
use App\Services\GuardianApiService;
use App\Services\NytApiService;
use App\Utils\ArticleTransformer;
use Illuminate\Console\Command;



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
    protected $description = 'Fetch and store articles from multiple APIs';
    protected $fetchers;
    protected $storageService;

    /**
     * Execute the console command.
     */
    public function __construct(
        NewsApiService        $newsApiService,
        GuardianApiService    $guardianApiService,
        NytApiService         $nytApiService,
        ArticleStorageService $storageService
    )
    {
        parent::__construct();
        $this->fetchers = [
            $newsApiService,
            $guardianApiService,
            $nytApiService
        ];
        $this->storageService = $storageService;
    }

    public function handle()
    {
        $articles = [];

        foreach ($this->fetchers as $fetcher) {
            $fetchedArticles = $fetcher->fetchArticles();
            foreach ($fetchedArticles as $article) {
                $articles[] = ArticleTransformer::transform($article, $fetcher->getSourceName());
            }
        }

        $this->storageService->store($articles);
        $this->info('Articles fetched and stored successfully!');
    }
}
