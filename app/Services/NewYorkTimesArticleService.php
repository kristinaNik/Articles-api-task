<?php

declare(strict_types=1);

namespace App\Services;

use App\Mappers\ArticleMapper;
use App\Models\Article;
use Illuminate\Support\Facades\Http;

class NewYorkTimesArticleService implements ArticleServiceInterface
{
	private string $apiKey;
	private ArticleMapper $articleMapper;

	public function __construct(ArticleMapper $articleMapper)
	{
		$this->apiKey = env('NEW_YORK_TIMES_API_KEY');
		$this->articleMapper = $articleMapper;
	}

	public function fetchArticles(): array
	{
		$response = Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
			'api-key' => $this->apiKey,
			'q' => 'latest',
			'sort' => 'newest',
			'limit' => 20,
		]);

		if ($response->successful()) {
			return $response->json('response.docs');
		}

		return [];
	}

	public function storeArticles(array $articles): void
	{
		$preparedArticles = $this->articleMapper->mapNewYorkTimesArticles($articles);
		foreach (array_chunk($preparedArticles, 100) as $chunk) {
			foreach ($chunk as $article) {
				Article::updateOrCreate(
					['url' => $article['url']],
					$article
				);
			}
		}
	}
}