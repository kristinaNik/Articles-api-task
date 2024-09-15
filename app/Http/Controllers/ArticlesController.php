<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedArticleResource;
use App\Services\CacheKeyService;
use App\Services\PaginationService;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class ArticlesController extends Controller
{
	public function __construct(
		private ArticleServiceInterface $articleService,
		private PaginationService $paginationService,
		private CacheKeyService $cacheKeyService
	)
	{

	}

	public function index(): JsonResponse
	{
		$articles = Article::all();
		return response()->json(ArticleResource::collection($articles));
	}

	public function search(Request $request): JsonResponse
	{
		$perPage = $request->input('per_page', 10);
		$cacheKey = $this->cacheKeyService->generateCacheKey($request, $perPage);

		// Try to fetch the cached result
		$articles = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($request, $perPage) {
			$query = Article::query();

			// Apply scopes based on request parameters
			if ($request->filled('title')) {
				$query->title($request->input('title'));
			}

			if ($request->filled('description')) {
				$query->description($request->input('description'));
			}

			if ($request->filled('source')) {
				$query->source($request->input('source'));
			}

			if ($request->filled('category')) {
				$query->category($request->input('category'));
			}

			if ($request->filled('author')) {
				$query->author($request->input('author'));
			}

			if ($request->filled('published_at')) {
				$query->publishedAt($request->input('published_at'));
			}

			return $query->paginate($perPage);
		});

		$pagination = $this->paginationService->generatePaginationData($articles);

		return response()->json(new PaginatedArticleResource([
			'data' => $articles,
			'pagination' => $pagination,
		]));
	}

	public function store(): JsonResponse
	{
		$articles = $this->articleService->fetchArticles();
		$this->articleService->storeArticles($articles);

		return response()->json(['message' => 'Articles fetched and stored successfully!']);
	}


}
