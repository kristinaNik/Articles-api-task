<?php

namespace App\Providers;

use App\Mappers\ArticleMapper;
use App\Services\ArticleSearchService;
use App\Services\ArticleSearchServiceInterface;
use App\Services\ArticleServiceInterface;
use App\Services\ArticleUrlExctractorInterface;
use App\Services\ArticleUrlExtractor;
use App\Services\GuardianArticleService;
use App\Services\NewsApiArticleService;
use App\Services\NewYorkTimesArticleService;
use App\Services\PaginationService;
use App\Services\PaginationServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
		$this->app->singleton(ArticleSearchServiceInterface::class, function () {
			return new ArticleSearchService();
		});

		$this->app->singleton(ArticleUrlExctractorInterface::class, function () {
			return new ArticleUrlExtractor();
		});

		$this->app->singleton(PaginationServiceInterface::class, function () {
			return new PaginationService();
		});

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
