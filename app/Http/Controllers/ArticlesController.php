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

		if ($request->has('title')) {
			$query->where('title', 'like', '%' . $request->input('title') . '%');
		}

		if ($request->has('description')) {
			$query->where('description', 'like', '%' . $request->input('description') . '%');
		}

		if ($request->has('source')) {
			$query->where('source', $request->input('source'));
		}

		if ($request->has('author')) {
			$query->where('author', 'like', '%' . $request->input('author') . '%');
		}

		if ($request->has('published_at')) {
			$query->whereDate('published_at', $request->input('published_at'));
		}

		$articles = $query->get();

		return response()->json(ArticleResource::collection($articles));
	}

	public function store(StoreArticlesRequest $request): JsonResponse
	{
		$articles = $this->articleService->fetchArticles();
		$this->articleService->storeArticles($articles);

		return response()->json(['message' => 'Articles fetched and stored successfully!']);
	}
}
