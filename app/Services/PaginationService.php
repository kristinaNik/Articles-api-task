<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService implements PaginationServiceInterface
{
	public function generatePaginationData(LengthAwarePaginator $paginator): array
	{
		return [
			'links' => [
				'first' => $paginator->url(1),
				'last' => $paginator->url($paginator->lastPage()),
				'prev' => $paginator->previousPageUrl(),
				'next' => $paginator->nextPageUrl(),
			],
			'meta' => [
				'current_page' => $paginator->currentPage(),
				'from' => $paginator->firstItem(),
				'last_page' => $paginator->lastPage(),
				'links' => [
					[
						'url' => $paginator->previousPageUrl(),
						'label' => '&laquo; Previous',
						'active' => !$paginator->onFirstPage(),
					],
					[
						'url' => $paginator->nextPageUrl(),
						'label' => 'Next &raquo;',
						'active' => $paginator->hasMorePages(),
					],
				],
				'next_page_url' => $paginator->nextPageUrl(),
				'path' => url()->current(),
				'per_page' => $paginator->perPage(),
				'prev_page_url' => $paginator->previousPageUrl(),
				'to' => $paginator->lastItem(),
				'total' => $paginator->total(),
			],
		];
	}
}