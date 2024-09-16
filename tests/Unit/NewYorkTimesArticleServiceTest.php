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

		$this->articleMapper = Mockery::mock(ArticleMapper::class);
		$this->service = new NewYorkTimesArticleService($this->articleMapper);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function testFetchArticlesReturnsExpectedData(): void
	{
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

		Http::fake([
			'https://api.nytimes.com/svc/search/v2/articlesearch.json' => Http::response($mockResponse, 200),
		]);

		$articles = $this->service->fetchArticles();

		// Verify the URL of each article
		foreach ($mockResponse['response']['docs'] as $index => $article) {
			$this->assertEquals($article['web_url'], $articles[$index]['web_url']);
		}
	}

	public function testStoreArticlesStoresArticlesCorrectly(): void
	{
		$mappedArticles = [
			['url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html', 'title' => 'Article 1', 'description' => 'Description for article 1', 'source' => 'nyt', 'published_at' => now()],
			['url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html', 'title' => 'Article 2', 'description' => 'Description for article 2', 'source' => 'nyt', 'published_at' => now()],
		];

		$this->articleMapper->shouldReceive('mapNewYorkTimesArticles')
			->andReturn($mappedArticles);

		Http::fake([
			'https://api.nytimes.com/svc/search/v2/articlesearch.json' => Http::response([
				'response' => [
					'docs' => [
						['web_url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html'],
						['web_url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html'],
					]
				]
			], 200),
		]);

		$this->service->storeArticles($mappedArticles);

		$this->assertDatabaseHas('articles', [
			'url' => 'https://www.nytimes.com/2024/09/16/business/economy/fed-interest-rates-labor.html',
			'title' => 'Article 1',
			'description' => 'Description for article 1',
			'source' => 'nyt',
			'published_at' => now()->format('Y-m-d H:i:s')
		]);
		$this->assertDatabaseHas('articles', [
			'url' => 'https://www.nytimes.com/2024/09/16/technology/tech-news.html',
			'title' => 'Article 2',
			'description' => 'Description for article 2',
			'source' => 'nyt',
			'published_at' => now()->format('Y-m-d H:i:s')
		]);
	}
}