<?php

namespace App\Repository;

use App\Models\Article;
use Illuminate\Support\Collection;

class ArticlesRepository implements ArticlesRepositoryInterface
{
	public function getArticlesByUrls(array $urls): Collection
	{
		return Article::whereIn('url', $urls)->get();
	}

}