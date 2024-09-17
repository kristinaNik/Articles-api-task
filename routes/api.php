<?php

use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Facades\Route;

// List all articles
Route::get('/articles', [ArticlesController::class, 'index']);

//List of sources
Route::get('/articles/source', [ArticlesController::class, 'getSources']);

// Show a specific article
Route::get('/articles/{id}', [ArticlesController::class, 'show']);

// Store a new article
Route::post('/articles', [ArticlesController::class, 'store']);

