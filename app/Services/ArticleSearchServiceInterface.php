<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleSearchServiceInterface
{
	public function searchArticlesWithFilters(array $filters): LengthAwarePaginator;
}