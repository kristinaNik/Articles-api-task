<?php

namespace App\Services;

interface ArticleUrlExctractorInterface
{
	public function extractArticleUrls(array $articlesData): array;
}