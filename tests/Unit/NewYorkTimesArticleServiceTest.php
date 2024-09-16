<?php

namespace Tests\Unit;

use App\Mappers\ArticleMapper;
use App\Services\NewYorkTimesArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class NewYorkTimesArticleServiceTest extends TestCase
{
	use RefreshDatabase;

	protected $articleMapper;
	protected NewYorkTimesArticleService $service;

	protected function setUp(): void
	{
		parent::setUp();

		// Mock ArticleMapper using createMock() from PHPUnit
		$this->articleMapper = $this->createMock(ArticleMapper::class);
		$this->service = new NewYorkTimesArticleService($this->articleMapper);
	}

	public function testFetchArticlesReturnsExpectedData(): void
	{
		// Mocked API response
		$mockResponse = [
			'response' => [
				'docs' => [
					[
						'web_url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html',
						'headline' => ['main' => 'First Article']
					],
					[
						'web_url' => 'https://www.nytimes.com/2024/09/16/nyregion/mta-funding-capital-projects.html',
						'headline' => ['main' => 'Second Article']
					]
				]
			]
		];

		// Fake the HTTP response for the New York Times API URL
		Http::fake([
			env('NEW_YORK_API_URL') => Http::response($mockResponse, 200),
		]);

		// Call the service to fetch articles
		$articles = $this->service->fetchArticles();

		// Assert that the fetched articles match the mocked response
		$this->assertCount(10, $articles);
		$this->assertEquals('https://www.nytimes.com/2024/09/16/business/harland-wolff-titanic-bankruptcy.html', $articles[0]['web_url']);
		$this->assertEquals('Harland & Wolff, Titanic Shipbuilder, Files for Bankruptcy', $articles[0]['headline']['main']);
		$this->assertEquals('https://www.nytimes.com/2024/09/16/world/europe/ukraine-russia-kyiv-drone-attack.html', $articles[1]['web_url']);
		$this->assertEquals('Russia Ramps Up Air Assault on Ukrainian Cities', $articles[1]['headline']['main']);
	}

	public function testStoreArticlesStoresArticlesCorrectly(): void
	{
		// Mocked mapped articles data that would come from the mapper
		$mappedArticles = [
			[
				'url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html',
				'title' => 'First Article',
				'description' => 'Description for article 1',
				'source' => 'nyt',
				'published_at' => now(),
			],
			[
				'url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html',
				'title' => 'Second Article',
				'description' => 'Description for article 2',
				'source' => 'nyt',
				'published_at' => now(),
			],
		];

		// Configure the mock for ArticleMapper to return mapped articles
		$this->articleMapper
			->expects($this->once())
			->method('mapNewYorkTimesArticles')
			->willReturn($mappedArticles);

		// Fake the HTTP response for the New York Times API
		Http::fake([
			env('NEW_YORK_API_URL') => Http::response([
				'response' => [
					'docs' => [
						[
							'web_url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html',
							'headline' => ['main' => 'First Article']
						],
						[
							'web_url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html',
							'headline' => ['main' => 'Second Article']
						]
					]
				]
			], 200),
		]);

		// Call the service to store articles
		$this->service->storeArticles($mappedArticles);

		// Check the database for stored articles
		$this->assertDatabaseHas('articles', [
			'url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html',
			'title' => 'First Article',
			'description' => 'Description for article 1',
			'source' => 'nyt',
			'published_at' => now()->format('Y-m-d H:i:s'),
		]);

		$this->assertDatabaseHas('articles', [
			'url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html',
			'title' => 'Second Article',
			'description' => 'Description for article 2',
			'source' => 'nyt',
			'published_at' => now()->format('Y-m-d H:i:s'),
		]);
	}
}