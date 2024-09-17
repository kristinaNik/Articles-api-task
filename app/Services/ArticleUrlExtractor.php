<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\ArticleUrlExctractorInterface;

class ArticleUrlExtractor implements ArticleUrlExctractorInterface
{
	public function extractArticleUrls(array $articlesData): array
	{
		$urls = [];

		foreach ($articlesData as $article) {
			if (isset($article['webUrl'])) {
				$urls[] = $article['webUrl'];
			}
			elseif (isset($article['web_url'])) {
				$urls[] = $article['web_url'];
			}
			elseif (isset($article['url'])) {
				$urls[] = $article['url'];
			}
		}

		return $urls;
	}
}