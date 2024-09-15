<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;

class CacheKeyService
{
	public function generateCacheKey(Request $request, $perPage): string
	{
		$key = 'search_' . $perPage;

		if ($request->filled('title')) {
			$key .= '_title_' . $request->input('title');
		}

		if ($request->filled('description')) {
			$key .= '_description_' . $request->input('description');
		}

		if ($request->filled('source')) {
			$key .= '_source_' . $request->input('source');
		}

		if ($request->filled('category')) {
			$key .= '_category_' . $request->input('category');
		}

		if ($request->filled('author')) {
			$key .= '_author_' . $request->input('author');
		}

		if ($request->filled('published_at')) {
			$key .= '_published_at_' . $request->input('published_at');
		}

		return md5($key);
	}
}