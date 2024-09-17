<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleSourceResource;
use App\Http\Resources\PaginatedArticleResource;
use App\Models\Article;
use App\Services\Interfaces\ArticleSearchServiceInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\ArticleUrlExctractorInterface;
use App\Services\Interfaces\PaginationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{
	public function __construct(
		private ArticleServiceInterface $articleService,
		private ArticleSearchServiceInterface $articleSearchService,
		private ArticleUrlExctractorInterface $articleUrlExtractor,
		private PaginationServiceInterface $paginationService
	)
	{}

	public function index(Request $request): Response
	{
		$filters = $request->only(['title', 'author', 'category', 'source', 'published_at']);

		// Determine if filters are applied
		$hasFilters = !empty(array_filter($filters));

		if ($hasFilters) {
			$articles = $this->articleSearchService->searchArticlesWithFilters($filters);
		} else {
			$articles = Article::paginate(10);
		}

		$pagination = $hasFilters
			? $this->paginationService->generatePaginationData($articles)
			: [
				'current_page' => $articles->currentPage(),
				'per_page' => $articles->perPage(),
				'total' => $articles->total(),
			];

		return response(new PaginatedArticleResource([
			'data' => $hasFilters ? $articles : $articles->items(),
			'pagination' => $pagination,
		]), 200);
	}

	public function getSources(): Response
	{
		$sources = DB::table('articles')->distinct()->pluck('source');

		return response(new ArticleSourceResource($sources), 200);
	}

	public function show(int $id): Response
	{
		$article = Article::find($id);

		if (!$article) {
			return response([
				'message' => 'Article not found.',
			], 404);
		}

		return response(new ArticleResource($article), 200);
	}

	public function store(): Response
	{
		try {
			$articlesData = $this->articleService->fetchArticles();

			$this->articleService->storeArticles($articlesData);

			// Fetch the newly stored articles from the database using their URLs
			$storedArticleUrls = $this->articleUrlExtractor->extractArticleUrls($articlesData);

			// Retrieve stored articles from the database based on the URLs
			$storedArticles = Article::whereIn('url', $storedArticleUrls)->get();

			return response([
				'message' => 'Articles fetched and stored successfully!',
				'data' => ArticleResource::collection($storedArticles),
			], 201);
		} catch (\Exception $e) {
			return response([
				'message' => 'An error occurred while fetching and storing articles.',
				'error' => $e->getMessage(),
			], 500);
		}
	}
}
