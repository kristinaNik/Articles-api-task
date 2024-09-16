<?php

declare(strict_types=1);

namespace App\Services;

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
		}

		return $urls;
	}

}