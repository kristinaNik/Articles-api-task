<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Services\Interfaces\ArticleSearchServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleSearchService implements ArticleSearchServiceInterface
{
	public function searchArticlesWithFilters(array $filters): LengthAwarePaginator
	{
		$query = Article::query();

		// Apply filters
		if (!empty($filters['title'])) {
			$query->where('title', 'like', '%' . $filters['title'] . '%');
		}

		if (!empty($filters['author'])) {
			$query->where('author', 'like', '%' . $filters['author'] . '%');
		}

		if (!empty($filters['category'])) {
			$query->where('category', 'like', '%' . $filters['category'] . '%');
		}

		if (!empty($filters['source'])) {
			$query->where('source', 'like', '%' . $filters['source'] . '%');
		}

		if (!empty($filters['published_at_from'])) {
			$query->whereDate('published_at', '>=', $filters['published_at_from']);
		}

		if (!empty($filters['published_at_to'])) {
			$query->whereDate('published_at', '<=', $filters['published_at_to']);
		}

		return $query->paginate(10);
	}
}