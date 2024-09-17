<?php

namespace Tests\Unit;

use App\Http\Controllers\ArticlesController;

use App\Models\Article;
use App\Repository\ArticlesRepositoryInterface;
use App\Services\Interfaces\ArticleSearchServiceInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\ArticleUrlExctractorInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	protected function createMockedDependencies(): array
	{
		$articleService = Mockery::mock(ArticleServiceInterface::class);
		$articleSearchService = Mockery::mock(ArticleSearchServiceInterface::class);
		$articleUrlExtractor = Mockery::mock(ArticleUrlExctractorInterface::class);
		$articleRepository = Mockery::mock(ArticlesRepositoryInterface::class);

		return compact('articleService',
			'articleSearchService',
			'articleUrlExtractor',
			'articleRepository'
		);
	}

	protected function createController($dependencies): ArticlesController
	{
		return new ArticlesController(
			$dependencies['articleService'],
			$dependencies['articleSearchService'],
			$dependencies['articleUrlExtractor'],
			$dependencies['articleRepository']
		);
	}

	protected function assertResponseOk($response, $statusCode = 200): void
	{
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals($statusCode, $response->getStatusCode());
	}

	protected function assertJsonResponse($response, $expectedMessage, $statusCode = 200)
	{
		$this->assertResponseOk($response, $statusCode);
		$responseData = json_decode($response->getContent(), true);
		$this->assertArrayHasKey('message', $responseData);
		$this->assertEquals($expectedMessage, $responseData['message']);
		return $responseData;
	}

	#[\PHPUnit\Framework\Attributes\DataProvider('indexProvider')]
	public function testIndex($filters, $expectedData)
	{
		$dependencies = $this->createMockedDependencies();
		$dependencies['articleSearchService']->shouldReceive('getArticlesByFilters')
			->with($filters)
			->andReturn($expectedData);

		$controller = $this->createController($dependencies);
		$request = Request::create('/articles', 'GET', $filters);
		$response = $controller->index($request);

		$this->assertResponseOk($response);
	}

	public static function indexProvider(): array
	{
		return [
			[ ['title' => 'Example Title'], ['data' => [], 'pagination' => ['pagination' => []]] ],
			[ [], ['data' => [], 'pagination' => ['pagination' => []]] ],
		];
	}

	public function testStoreSuccess()
	{
		$articlesData = [['title' => 'Example Article', 'url' => 'https://example.com/article']];

		$dependencies = $this->createMockedDependencies();
		$dependencies['articleService']->shouldReceive('fetchArticles')
			->once()
			->andReturn($articlesData);
		$dependencies['articleService']->shouldReceive('storeArticles')
			->once()
			->with($articlesData);
		$dependencies['articleUrlExtractor']->shouldReceive('extractArticleUrls')
			->once()
			->with($articlesData)
			->andReturn(['https://example.com/article']);
		$dependencies['articleRepository']->shouldReceive('getArticlesByUrls')
			->once()
			->with(['https://example.com/article'])
			->andReturn(collect([new Article(['url' => 'https://example.com/article'])]));

		$controller = $this->createController($dependencies);
		$response = $controller->store();

		$this->assertJsonResponse(
			$response,
			'Articles fetched and stored successfully!',
			201
		);
	}

	public function testStoreFailure()
	{
		$dependencies = $this->createMockedDependencies();
		$dependencies['articleService']->shouldReceive('fetchArticles')
			->once()
			->andThrow(new \Exception('Service failure'));

		$controller = $this->createController($dependencies);
		$response = $controller->store();

		$responseData = $this->assertJsonResponse(
			$response,
			'An error occurred while fetching and storing articles.', 500
		);
		$this->assertArrayHasKey('error', $responseData);
		$this->assertEquals('Service failure', $responseData['error']);
	}
}