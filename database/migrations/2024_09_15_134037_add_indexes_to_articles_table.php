<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{

		Schema::table('articles', function (Blueprint $table) {
			$table->index('title', 'articles_title_index');
			$table->index('source', 'articles_source_index');
			$table->index('published_at', 'articles_published_at_index');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::table('articles', function (Blueprint $table) {
			// Dropping indexes
			$table->dropIndex('articles_title_index');
			$table->dropIndex('articles_source_index');
			$table->dropIndex('articles_published_at_index');
		});
	}
};
