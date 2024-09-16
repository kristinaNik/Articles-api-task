<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginationServiceInterface
{
	public function generatePaginationData(LengthAwarePaginator $paginator): array;
}