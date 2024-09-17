<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleSearchServiceInterface
{
	public function searchArticlesWithFilters(array $filters): LengthAwarePaginator;
}