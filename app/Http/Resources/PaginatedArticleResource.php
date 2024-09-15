<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedArticleResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'data' => ArticleResource::collection($this->resource['data']),
			'pagination' => $this->resource['pagination'],
		];
	}
}
