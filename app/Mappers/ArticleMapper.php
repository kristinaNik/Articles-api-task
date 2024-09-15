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
				'category' => $article['sectionName'],
				'author' => $article['fields']['byline'] ?? 'Unknown',
				'url' => $article['webUrl'],
				'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
				'created_at' => now(),
				'updated_at' => now(),
			];
		}, $articles);
	}

	public function mapNewYorkTimesArticles(array $articles): array
	{
		return array_map(function ($article) {
			$authors = isset($article['byline']['person']) && is_array($article['byline']['person'])
				? implode(', ', array_column($article['byline']['person'], 'name'))
				: 'Unknown';

			return [
				'title' => $article['headline']['main'],
				'description' => $article['abstract'] ?? 'No description available.',
				'source' => 'The New York Times',
				'category' => $article['section'] ?? 'General',
				'author' => $authors,
				'url' => $article['web_url'],
				'published_at' =>  isset($article['pub_date'])
					? Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s')
					: null,
				'created_at' => now(),
				'updated_at' => now(),
			];
		}, $articles);
	}
}