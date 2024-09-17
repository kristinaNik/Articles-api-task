<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginationServiceInterface
{
	public function generatePaginationData(LengthAwarePaginator $paginator): array;
}