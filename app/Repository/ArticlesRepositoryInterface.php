<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface ArticlesRepositoryInterface
{
	public function getArticlesByUrls(array $urls): Collection;
}