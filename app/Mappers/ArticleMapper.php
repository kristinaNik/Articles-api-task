<?php

namespace App\Mappers;

use Carbon\Carbon;

class ArticleMapper
{
	public function mapGuardianArticles(array $articles): array
	{
		return  array_map(function ($article) {
			return [
				'title' => $article['webTitle'],
				'description' => $article['fields']['trailText'] ?? 'No description available.',
				'source' => 'The Guardian',
				'author' => $article['fields']['byline'] ?? 'Unknown',
				'url' => $article['webUrl'],
				'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
				'created_at' => now(),
				'updated_at' => now(),
			];
		}, $articles);
	}

	public function mapNewYorkTimesArticles(): array
	{
		//todo
	}
}