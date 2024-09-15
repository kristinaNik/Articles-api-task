<?php

namespace App\Services;

interface ArticleServiceInterface
{
	public function fetchArticles(): array;

	public function storeArticles(array $articles): void;

}