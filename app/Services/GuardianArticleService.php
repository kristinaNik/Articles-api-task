<?php

declare(strict_types=1);

namespace App\Services;

use App\Mappers\ArticleMapper;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Support\Facades\Http;

class GuardianArticleService implements ArticleServiceInterface
{
	private string $apiKey;
	private ArticleMapper $articleMapper;

	public function __construct(ArticleMapper $articleMapper)
	{
		$this->apiKey = env('GUARDIAN_API_KEY');
		$this->articleMapper = $articleMapper;
	}
	public function fetchArticles(): array
	{
		$response = Http::get(env('GUARDIAN_API_URL'), [
			'api-key' => $this->apiKey,
			'format' => 'json',
			'page-size' => 20,
		]);

		if ($response->successful()) {
			return $response->json('response.results');
		}

		return [];
	}

	public function storeArticles(array $articles): void
	{
		$articles = $this->articleMapper->mapGuardianArticles($articles);

		foreach ($articles as $article) {
			$article->save();
		}
	}
}