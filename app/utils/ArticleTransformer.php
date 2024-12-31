<?php

namespace App\Utils;

use Carbon\Carbon;

class ArticleTransformer
{
    public static function transform(array $data, string $source, string $defaultCategory = 'General'): array
    {
        return [
            'title' => $data['title'] ?? 'Untitled',
            'description' => $data['description'] ?? 'No description available',
            'author' => $data['author'] ?? 'Unknown',
            'source' => $source,
            'category' => $data['category'] ?? $defaultCategory,
            'published_at' => isset($data['publishedAt']) ? Carbon::parse($data['publishedAt'])->toDateTimeString() : now(),
            'url' => $data['url'],
        ];
    }
}
