<?php

namespace App\Providers;

use App\Mappers\ArticleMapper;
use App\Repository\ArticlesRepository;
use App\Repository\ArticlesRepositoryInterface;
use App\Services\ArticleSearchService;
use App\Services\ArticleUrlExtractor;
use App\Services\GuardianArticleService;
use App\Services\Interfaces\ArticleSearchServiceInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\ArticleUrlExctractorInterface;
use App\Services\Interfaces\PaginationServiceInterface;
use App\Services\NewsApiArticleService;
use App\Services\NewYorkTimesArticleService;
use App\Services\PaginationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
		$this->app->singleton(ArticleSearchServiceInterface::class, function () {
			return new ArticleSearchService(new PaginationService());
		});

		$this->app->singleton(ArticleUrlExctractorInterface::class, function () {
			return new ArticleUrlExtractor();
		});

		$this->app->singleton(PaginationServiceInterface::class, function () {
			return new PaginationService();
		});

		$this->app->bind(ArticlesRepositoryInterface::class, ArticlesRepository::class);

		$this->app->singleton(ArticleMapper::class);

		// Determine which service to bind based on configuration
		$service = config('services.article_service');

		if ($service === 'guardian') {
			$this->app->bind(ArticleServiceInterface::class, GuardianArticleService::class);
		} elseif ($service === 'new_york_times') {
			$this->app->bind(ArticleServiceInterface::class, NewYorkTimesArticleService::class);
		} elseif ($service === 'news') {
			$this->app->bind(ArticleServiceInterface::class, NewsApiArticleService::class);
		}
		else {
			throw new \Exception("Unsupported article service: $service");
		}
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
