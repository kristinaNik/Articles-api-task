<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedArticleResource;
use App\Services\ArticleSearchServiceInterface;
use App\Services\ArticleUrlExctractorInterface;
use App\Services\PaginationService;
use App\Services\PaginationServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
	public function __construct(
		private ArticleServiceInterface $articleService,
		private ArticleSearchServiceInterface $articleSearchService,
		private ArticleUrlExctractorInterface $articleUrlExtractor,
		private PaginationServiceInterface $paginationService
	)
	{

	}

	/**
	 * Display a list of all articles.
	 */
	public function index(): Response
	{
		$articles = Article::paginate(10);
		return response(ArticleResource::collection($articles), 200);
	}

	public function search(Request $request): Response
	{
		$articles = $this->articleSearchService->searchArticlesWithFilters($request);
		$pagination = $this->paginationService->generatePaginationData($articles);

		return response(new PaginatedArticleResource([
			'data' => $articles,
			'pagination' => $pagination,
		]), 200);
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
