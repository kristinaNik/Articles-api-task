<?php

namespace Tests\Unit;

use App\Http\Controllers\ArticlesController;

use App\Services\Interfaces\ArticleSearchServiceInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\ArticleUrlExctractorInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function testIndexWithFilters()
	{
		$filters = ['title' => 'Example Title'];

		$paginator = Mockery::mock(LengthAwarePaginator::class);
		$paginator->shouldReceive('toArray')->andReturn(['data' => [], 'pagination' => []]);

		$articleSearchService = Mockery::mock(ArticleSearchServiceInterface::class);
		$articleSearchService->shouldReceive('getArticlesByFilters')->with($filters)->andReturn([
			'data' => [],
			'pagination' => ['pagination' => []],
		]);

		$articleService = Mockery::mock(ArticleServiceInterface::class);
		$articleUrlExtractor = Mockery::mock(ArticleUrlExctractorInterface::class);

		$controller = new ArticlesController($articleService, $articleSearchService, $articleUrlExtractor);

		$request = Request::create('/articles', 'GET', $filters);

		$response = $controller->index($request);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testIndexWithoutFilters()
	{
		$paginator = Mockery::mock(LengthAwarePaginator::class);
		$paginator->shouldReceive('toArray')->andReturn(['data' => [], 'pagination' => []]);

		$articleSearchService = Mockery::mock(ArticleSearchServiceInterface::class);
		$articleSearchService->shouldReceive('getArticlesByFilters')->with([])->andReturn([
			'data' => [],
			'pagination' => ['pagination' => []],
		]);

		$articleService = Mockery::mock(ArticleServiceInterface::class);
		$articleUrlExtractor = Mockery::mock(ArticleUrlExctractorInterface::class);

		$controller = new ArticlesController($articleService, $articleSearchService, $articleUrlExtractor);

		$request = Request::create('/articles');

		$response = $controller->index($request);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
	}
}