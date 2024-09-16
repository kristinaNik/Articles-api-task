<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleSearchService implements ArticleSearchServiceInterface
{
	public function searchArticlesWithFilters(Request $request): LengthAwarePaginator
	{
		$query = Article::query();

		if ($request->filled('title')) {
			$query->title($request->input('title'));
		}

		if ($request->filled('description')) {
			$query->description($request->input('description'));
		}

		if ($request->filled('source')) {
			$query->source($request->input('source'));
		}

		if ($request->filled('category')) {
			$query->category($request->input('category'));
		}

		if ($request->filled('author')) {
			$query->author($request->input('author'));
		}

		if ($request->filled('published_at')) {
			$query->publishedAt($request->input('published_at'));
		}

		return $query->paginate($request->input('per_page', 10));

	}
}