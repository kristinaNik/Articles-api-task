<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedArticleResource;
use App\Services\PaginationService;
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
		private PaginationService $paginationService
	)
	{

	}

	public function index(): Response
	{
		$articles = Article::all();
		return response(ArticleResource::collection($articles), 200);
	}

	public function search(Request $request): Response
	{
		$perPage = $request->input('per_page', 10);
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

		if ($request->filled('category')) {
			$query->category($request->input('category'));
		}

		if ($request->filled('author')) {
			$query->author($request->input('author'));
		}

		if ($request->filled('published_at')) {
			$query->publishedAt($request->input('published_at'));
		}

		$articles = $query->paginate($perPage);
		$pagination = $this->paginationService->generatePaginationData($articles);

		return response(new PaginatedArticleResource([
			'data' => $articles,
			'pagination' => $pagination,
		]), 200);
	}

	public function store(): Response
	{
		$articlesData = $this->articleService->fetchArticles();
		$this->articleService->storeArticles($articlesData);

		// Fetch the newly stored articles from the database using their URLs
		$storedArticleUrls = array_column($articlesData, 'webUrl');

		// Retrieve stored articles from the database based on the URLs
		$storedArticles = Article::whereIn('url', $storedArticleUrls)->get();

		return response([
			'message' => 'Articles fetched and stored successfully!',
			'data' => ArticleResource::collection($storedArticles),
		], 201);
	}


}
