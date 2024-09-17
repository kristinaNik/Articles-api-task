<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Services\Interfaces\ArticleSearchServiceInterface;
use App\Services\Interfaces\PaginationServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleSearchService implements ArticleSearchServiceInterface
{
	public function __construct(private PaginationServiceInterface $paginationService) {}

	public function getArticlesByFilters(array $filters): array
	{
		if (!empty(array_filter($filters))) {
			$articles = $this->searchArticlesWithFilters($filters);
		} else {
			$articles = Article::paginate(10);
		}

		return [
			'data' => $articles,
			'pagination' => $this->generatePaginationData($articles, !empty(array_filter($filters)))
		];
	}

	private function searchArticlesWithFilters(array $filters): LengthAwarePaginator
	{
		$query = Article::query();

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

	private function generatePaginationData(LengthAwarePaginator $articles, bool $hasFilters): array
	{
		return $hasFilters
			? $this->paginationService->generatePaginationData($articles)
			: $this->getDefaultPaginationData($articles);
	}

	private function getDefaultPaginationData(LengthAwarePaginator $articles): array
	{
		return [
			'current_page' => $articles->currentPage(),
			'per_page' => $articles->perPage(),
			'total' => $articles->total(),
		];
	}
}
