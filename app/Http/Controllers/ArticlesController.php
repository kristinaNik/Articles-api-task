<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
	public function __construct(private ArticleServiceInterface $articleService) {

	}

	public function index(): JsonResponse
	{
		$articles = Article::all();
		return response()->json(ArticleResource::collection($articles));
	}

	public function search(Request $request)
	{
		$query = Article::query();

		if ($request->filled('title')) {
			$query->title($request->input('title'));
		}

		if ($request->filled('description')) {
			$query->description($request->input('description'));
		}

		if ($request->filled('source')) {
			$query->source($request->input('source'));
		}

		if ($request->filled('author')) {
			$query->author($request->input('author'));
		}

		if ($request->filled('published_at')) {
			$query->publishedAt($request->input('published_at'));
		}

		// Paginate results
		$articles = $query->paginate(10);

		return response()->json(ArticleResource::collection($articles));
	}

	public function store(): JsonResponse
	{
		$articles = $this->articleService->fetchArticles();
		$this->articleService->storeArticles($articles);

		return response()->json(['message' => 'Articles fetched and stored successfully!']);
	}
}
