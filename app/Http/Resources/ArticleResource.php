<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'url' => $this->url,
			'title' => $this->title,
			'description' => $this->description,
			'source' => $this->source,
			'author' => $this->author,
			'published_at' => $this->published_at,
		];
	}
}
