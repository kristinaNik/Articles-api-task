<?php

namespace App\Services\Interfaces;

interface ArticleUrlExctractorInterface
{
	public function extractArticleUrls(array $articlesData): array;
}