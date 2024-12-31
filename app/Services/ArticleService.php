<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function getFilteredArticles(array $filters)
    {
        $query = Article::query();

        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['categories']) && is_array($filters['categories'])) {
            $query->whereIn('category', $filters['categories']);
        }

        if (isset($filters['source']) && $filters['source']) {
            $query->where('source', $filters['source']);
        }

        if (isset($filters['sources']) && is_array($filters['sources'])) {
            $query->whereIn('source', $filters['sources']);
        }

        if (isset($filters['author']) && $filters['author']) {
            $query->where('author', 'LIKE', '%' . $filters['author'] . '%');
        }

        if (isset($filters['date']) && $filters['date']) {
            $query->whereDate('published_at', $filters['date']);
        }

        if (isset($filters['query']) && $filters['query']) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', '%' . $filters['query'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $filters['query'] . '%');
            });
        }

        return $query->paginate(10);
    }
}
