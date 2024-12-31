<?php

namespace App\Services;

interface ArticleFetcherInterface
{
    public function fetchArticles(): array;
    public function getSourceName(): string;
}
