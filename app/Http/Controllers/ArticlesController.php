<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

	public function store(StoreArticlesRequest $request): JsonResponse
	{
		$articles = $this->articleService->fetchArticles();
		$this->articleService->storeArticles($articles);

		return response()->json(['message' => 'Articles fetched and stored successfully!']);
	}
}
