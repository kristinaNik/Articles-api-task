<?php

namespace App\Services\Interfaces;

interface ArticleServiceInterface
{
	public function fetchArticles(): array;

	public function storeArticles(array $articles): void;

}