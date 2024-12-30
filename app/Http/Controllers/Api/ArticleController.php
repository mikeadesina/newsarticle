<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
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


        $query = Article::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('category', $request->categories);
        }


        if ($request->has('source') && $request->source) {
            $query->where('source', $request->source);
        }

        if ($request->has('sources') && is_array($request->sources)) {
            $query->whereIn('source', $request->sources);
        }

        if ($request->has('author') && $request->author) {
            $query->where('author', 'LIKE', "%{$request->author}%");
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('published_at', $request->date);
        }

        if ($request->has('query') && $request->input('query')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->input('query')}%")
                    ->orWhere('description', 'LIKE', "%{$request->input('query')}%");
            });
        }


        $articles = $query->paginate(10);

        return response()->json($articles);
    }



}
