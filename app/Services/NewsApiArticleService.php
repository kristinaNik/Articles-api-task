<?php

namespace App\Services;

use App\Mappers\ArticleMapper;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Support\Facades\Http;

class NewsApiArticleService implements ArticleServiceInterface
{
	private string $apiKey;
	private ArticleMapper $articleMapper;

	public function __construct(ArticleMapper $articleMapper)
	{
		$this->apiKey = env('NEWS_API_KEY');
		$this->articleMapper = $articleMapper;
	}

	public function fetchArticles(): array
	{
		$response = Http::get(env('NEWS_API_URL'), [
			'apiKey' => $this->apiKey,
			'country' => 'us',
			'pageSize' => 20,
		]);

		if ($response->successful()) {
			return $response->json('articles');
		}

		return [];
	}

	public function storeArticles(array $articles): void
	{
		$articles = $this->articleMapper->mapNewsApiArticles($articles);

		foreach ($articles as $article) {
			$article->save();
		}
	}
}