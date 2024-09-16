<?php

namespace App\Console\Commands;

use App\Services\ArticleServiceInterface;
use Illuminate\Console\Command;

class FetchAndStoreArticles extends Command
{
	protected $signature = 'articles:fetch-and-store';
	protected $description = 'Fetch and store articles from external sources';

	private ArticleServiceInterface $articleService;

	public function __construct(ArticleServiceInterface $articleService)
	{
		parent::__construct();
		$this->articleService = $articleService;
	}

	public function handle()
	{
		$this->info('Fetching articles...');
		try {
			$articles = $this->articleService->fetchArticles();
			$this->articleService->storeArticles($articles);
			$this->info('Articles fetched and stored successfully.');
		} catch (\Exception $e) {
			$this->error('Error fetching or storing articles: ' . $e->getMessage());
		}
	}
}
