<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::table('articles', function (Blueprint $table) {
			// Add the 'category' column
			$table->string('category')->nullable()->after('source');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('articles', function (Blueprint $table) {
			$table->dropColumn('category');
		});
    }
};
