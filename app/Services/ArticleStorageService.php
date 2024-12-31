<?php

namespace App\Services;

use App\Models\Article;

class ArticleStorageService
{
    public function store(array $articles): void
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                $article
            );
        }
    }
}
