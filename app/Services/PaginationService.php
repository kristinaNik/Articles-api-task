<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\PaginationServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

class PaginationService implements PaginationServiceInterface
{
	/**
	 * Generate pagination data from a LengthAwarePaginator instance.
	 *
	 * @param LengthAwarePaginator $paginator
	 * @return array
	 */
	public function generatePaginationData(LengthAwarePaginator $paginator): array
	{
		return [
			'links' => $this->getPaginationLinks($paginator),
			'meta' => $this->getPaginationMeta($paginator),
		];
	}

	/**
	 * Get pagination links.
	 *
	 * @param LengthAwarePaginator $paginator
	 * @return array
	 */
	private function getPaginationLinks(LengthAwarePaginator $paginator): array
	{
		return [
			'first' => $paginator->url(1),
			'last' => $paginator->url($paginator->lastPage()),
			'prev' => $paginator->previousPageUrl(),
			'next' => $paginator->nextPageUrl(),
		];
	}

	/**
	 * Get pagination meta data.
	 *
	 * @param LengthAwarePaginator $paginator
	 * @return array
	 */
	private function getPaginationMeta(LengthAwarePaginator $paginator): array
	{
		return [
			'current_page' => $paginator->currentPage(),
			'from' => $paginator->firstItem(),
			'last_page' => $paginator->lastPage(),
			'links' => $this->getPaginationLinkLabels($paginator),
			'next_page_url' => $paginator->nextPageUrl(),
			'path' => URL::current(),
			'per_page' => $paginator->perPage(),
			'prev_page_url' => $paginator->previousPageUrl(),
			'to' => $paginator->lastItem(),
			'total' => $paginator->total(),
		];
	}

	/**
	 * Get pagination link labels.
	 *
	 * @param LengthAwarePaginator $paginator
	 * @return array
	 */
	private function getPaginationLinkLabels(LengthAwarePaginator $paginator): array
	{
		return [
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
		];
	}
}