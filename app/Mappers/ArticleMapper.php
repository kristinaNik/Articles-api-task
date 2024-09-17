<?php

namespace App\Mappers;

use App\Models\Article;
use Carbon\Carbon;

class ArticleMapper
{
	public function mapGuardianArticles(array $articles): array
	{
		return array_map(function ($article) {
			return new Article([
				'title' => $article['webTitle'],
				'description' => $article['fields']['trailText'] ?? 'No description available.',
				'source' => 'The Guardian',
				'category' => $article['sectionName'],
				'author' => $article['fields']['byline'] ?? 'Unknown',
				'url' => $article['webUrl'],
				'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
			]);
		}, $articles);
	}

	public function mapNewYorkTimesArticles(array $articles): array
	{
		return array_map(function ($article) {
			return new Article([
				'title' => $article['headline']['main'],
				'description' => $article['abstract'] ?? 'No description available.',
				'source' => 'The New York Times',
				'category' => $article['section_name'] ?? 'General',
				'author' => $article['byline']['original'] ?? 'Unknown',
				'url' => $article['web_url'],
				'published_at' => isset($article['pub_date'])
					? Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s')
					: null,
			]);
		}, $articles);
	}

	public function mapNewsApiArticles(array $articles): array
	{
		return array_map(function ($article) {
			return new Article([
				'url' => $article['url'],
				'title' => $article['title'],
				'description' => $article['description'] ?? 'No description available.',
				'source' => $article['source']['name'] ?? 'Unknown',
				'author' => $article['author'] ?? 'no author',
				'published_at' => isset($article['publishedAt'])
					? Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s')
					: null,
			]);
		}, $articles);
	}
}