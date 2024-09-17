<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleSearchServiceInterface
{
	public function getArticlesByFilters(array $filters): array;
}