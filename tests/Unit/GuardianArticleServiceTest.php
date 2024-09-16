<?php

namespace Tests\Unit;

use App\Mappers\ArticleMapper;
use App\Services\GuardianArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class GuardianArticleServiceTest extends TestCase
{
	use RefreshDatabase;

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	protected function setUp(): void
	{
		parent::setUp();

		$this->articleMapper = Mockery::mock(ArticleMapper::class);
		$this->service = new GuardianArticleService($this->articleMapper);
	}

	public function testFetchArticlesWithVariousCriteria(): void
	{
		Http::fake([
			'https://content.guardianapis.com/search*' => Http::response([
				'response' => [
					'results' => [
						['id' => '1', 'webUrl' => 'http://example.com/1', 'title' => 'First Article'],
						['id' => '2', 'webUrl' => 'http://example.com/2', 'title' => 'Second Article'],
					]
				]
			])
		]);

		$articles = $this->service->fetchArticles();
		$this->assertCount(2, $articles);
		$this->assertEquals('http://example.com/1', $articles[0]['webUrl']);
		$this->assertEquals('http://example.com/2', $articles[1]['webUrl']);

		Http::fake([
			'https://content.guardianapis.com/search*title=Breaking+News*' => Http::response([
				'response' => [
					'results' => [
						['id' => '1', 'webUrl' => 'http://example.com/1', 'title' => 'Breaking News: Event'],
						['id' => '2', 'webUrl' => 'http://example.com/2', 'title' => 'Breaking News: Update'],
					]
				]
			])
		]);

		$articles = $this->service->fetchArticles();

		$this->assertCount(2, $articles);
		$this->assertEquals('http://example.com/1', $articles[0]['webUrl']);
		$this->assertEquals('http://example.com/2', $articles[1]['webUrl']);
	}

	public function testStoreArticlesStoresArticlesCorrectly(): void
	{
		$mappedArticles = [
			['url' => 'http://example.com/1', 'title' => 'Article 1', 'description' => 'Description for article 1', 'source' => 'guardian', 'published_at' => now()],
			['url' => 'http://example.com/2', 'title' => 'Article 2', 'description' => 'Description for article 2', 'source' => 'guardian', 'published_at' => now()],
			['url' => 'http://example.com/3', 'title' => 'Article 3', 'description' => 'Description for article 3', 'source' => 'guardian', 'published_at' => now()],
		];

		$this->articleMapper->shouldReceive('mapGuardianArticles')
			->andReturn($mappedArticles);

		Http::fake([
			'https://content.guardianapis.com/search' => Http::response([
				'response' => [
					'results' => [
						['id' => '1', 'webUrl' => 'http://example.com/1'],
						['id' => '2', 'webUrl' => 'http://example.com/2'],
						['id' => '3', 'webUrl' => 'http://example.com/3'],
					]
				]
			])
		]);


		$this->service->storeArticles($mappedArticles);

		$this->assertDatabaseHas('articles', [
			'url' => 'http://example.com/1',
			'title' => 'Article 1',
			'description' => 'Description for article 1',
			'source' => 'guardian',
			'published_at' => now()->format('Y-m-d H:i:s')
		]);
		$this->assertDatabaseHas('articles', [
			'url' => 'http://example.com/2',
			'title' => 'Article 2',
			'description' => 'Description for article 2',
			'source' => 'guardian',
			'published_at' => now()->format('Y-m-d H:i:s')
		]);
		$this->assertDatabaseHas('articles', [
			'url' => 'http://example.com/3',
			'title' => 'Article 3',
			'description' => 'Description for article 3',
			'source' => 'guardian',
			'published_at' => now()->format('Y-m-d H:i:s')
		]);
	}
}