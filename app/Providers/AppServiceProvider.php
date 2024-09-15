<?php

namespace App\Providers;

use App\Mappers\ArticleMapper;
use App\Services\ArticleServiceInterface;
use App\Services\CacheKeyService;
use App\Services\GuardianArticleService;
use App\Services\NewYorkTimesArticleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
		$this->app->singleton(CacheKeyService::class, function () {
			return new CacheKeyService();
		});

		$this->app->singleton(ArticleMapper::class);

		// Determine which service to bind based on configuration
		$service = config('services.article_service');

		if ($service === 'guardian') {
			$this->app->bind(ArticleServiceInterface::class, GuardianArticleService::class);
		} elseif ($service === 'new_york_times') {
			$this->app->bind(ArticleServiceInterface::class, NewYorkTimesArticleService::class);
		} else {
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
