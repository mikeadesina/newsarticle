<?php

namespace App\Http\Controllers\Api;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'string|nullable',
            'source' => 'string|nullable',
            'author' => 'string|nullable',
            'date' => 'date|nullable',
            'query' => 'string|nullable',
            'categories' => 'array|nullable',
            'categories.*' => 'string',
            'sources' => 'array|nullable',
            'sources.*' => 'string',
        ]);

        \Log::info('Query Parameter:', ['query' => $request->input('query')]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filters = $request->all();
        $articles = $this->articleService->getFilteredArticles($filters);

        return response()->json($articles);
    }
}

