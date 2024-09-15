<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

	protected $table = 'articles';

	protected $fillable = [
		'title',
		'description',
		'source',
		'author',
		'url',
		'published_at',
	];

	public $timestamps = true;

	public function scopeTitle($query, $title)
	{
		return $query->where('title', 'like', '%' . $title . '%');
	}

	public function scopeDescription($query, $description)
	{
		return $query->where('description', 'like', '%' . $description . '%');
	}

	public function scopeSource($query, $source)
	{
		return $query->where('source', $source);
	}

	public function scopeAuthor($query, $author)
	{
		return $query->where('author', 'like', '%' . $author . '%');
	}

	public function scopePublishedAt($query, $date)
	{
		return $query->whereDate('published_at', $date);
	}
}
