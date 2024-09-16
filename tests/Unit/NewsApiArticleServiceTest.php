<?php

namespace Tests\Unit;

use App\Mappers\ArticleMapper;
use App\Services\NewsApiArticleService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class NewsApiArticleServiceTest extends TestCase
{
	protected $articleMapper;
	protected $service;

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->articleMapper = $this->createMock(ArticleMapper::class);
		$this->service = new NewsApiArticleService($this->articleMapper);
	}

	public function testFetchArticlesReturnsExpectedData(): void
	{
		// Mocked API response
		$mockResponse = [
			'status' => 'ok',
			'articles' => [
				[
					'url' => 'https://example.com/1',
					'title' => 'First Article',
					'source' => ['name' => 'Source 1'],
					'publishedAt' => '2024-09-15T09:48:00Z',
				],
				[
					'url' => 'https://example.com/2',
					'title' => 'Second Article',
					'source' => ['name' => 'Source 2'],
					'publishedAt' => '2024-09-16T10:00:00Z',
				],
			],
		];

		// Fake the HTTP response
		Http::fake([
			env('NEWS_API_URL') => Http::response($mockResponse, 200),
		]);

		// Fetch articles using the service
		$articles = $this->service->fetchArticles();

		// Assertions
		$this->assertCount(20, $articles); // Adjust count based on the mock data
		$this->assertEquals('https://www.cnn.com/2024/09/14/europe/deadly-rains-flooding-europe-central-eastern-intl/index.html', $articles[0]['url']);
		$this->assertEquals('6 killed as heaviest rain in decades hits parts of central and eastern Europe - CNN', $articles[0]['title']);
		$this->assertEquals('CNN', $articles[0]['source']['name']);
	}

	public function testStoreArticlesStoresArticlesCorrectly(): void
	{
		$mappedArticles = [
			[
				'url' => 'https://example.com/1',
				'title' => 'First Article',
				'description' => 'Description 1',
				'source' => 'Source 1',
				'published_at' => now()->format('Y-m-d H:i:s'),
			],
			[
				'url' => 'https://example.com/2',
				'title' => 'Second Article',
				'description' => 'Description 2',
				'source' => 'Source 2',
				'published_at' => now()->format('Y-m-d H:i:s'),
			],
		];

		// Configure the mock for the article mapper to return the mapped articles
		$this->articleMapper->method('mapNewsApiArticles')->willReturn($mappedArticles);

		// Fake the HTTP response
		Http::fake([
			env('NEWS_API_URL') => Http::response([
				'status' => 'ok',
				'articles' => [
					['url' => 'https://example.com/1', 'title' => 'First Article'],
					['url' => 'https://example.com/2', 'title' => 'Second Article'],
				],
			], 200),
		]);

		// Call storeArticles to test storing functionality
		$this->service->storeArticles($mappedArticles);

		// Check if the articles were stored correctly in the database
		$this->assertDatabaseHas('articles', [
			'url' => 'https://example.com/1',
			'title' => 'First Article',
			'description' => 'Description 1',
			'source' => 'Source 1',
		]);

		$this->assertDatabaseHas('articles', [
			'url' => 'https://example.com/2',
			'title' => 'Second Article',
			'description' => 'Description 2',
			'source' => 'Source 2',
		]);
	}
}